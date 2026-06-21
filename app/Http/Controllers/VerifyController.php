<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVerificationRequest;
use App\Models\Model3D;
use App\Models\VerificationRequest;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function index(Request $r)
    {
        $user = $r->user();
        $pendingRequest = VerificationRequest::where('user_id', $r->user()->id)
            ->where('request_status', 'pending')
            ->latest()
            ->first();

        $verificationCheck = $this->verificationEligibility($user);

        if ($r->wantsJson()) {
            return $this->apiData([
                'pending_request' => $pendingRequest,
                'verification_check' => $verificationCheck,
                'is_staff' => $user->isStaff(),
            ]);
        }

        return view('verify.index', compact('pendingRequest', 'verificationCheck'));
    }

    public function store(StoreVerificationRequest $request)
    {
        if ($request->user()->isStaff()) {
            $message = 'Staff accounts already have elevated access and do not need to request verification.';

            return $request->wantsJson()
                ? $this->apiError($message, 403)
                : back()->with('error', $message);
        }

        $data = $request->validated();

        $verificationCheck = $this->verificationEligibility($request->user());
        if (! $verificationCheck['eligible']) {
            return $request->wantsJson()
                ? $this->apiError(
                    $verificationCheck['message'],
                    403,
                    [
                        'verification_check' => $verificationCheck,
                    ]
                )
                : back()->with('error', $verificationCheck['message']);
        }

        $existing = VerificationRequest::where('user_id', $request->user()->id)
            ->where('request_status', 'pending')
            ->first();

        if ($existing) {
            return $request->wantsJson()
                ? $this->apiError('You already have a pending verification request.', 409)
                : back()->with('error', 'You already have a pending verification request.');
        }

        $verificationRequest = VerificationRequest::create([
            'user_id' => $request->user()->id,
            'note' => $data['note'],
            'request_status' => 'pending',
        ]);

        return $request->wantsJson()
            ? $this->apiData([
                'verification_request' => $verificationRequest,
            ], 'Verification request submitted.', 201)
            : back()->with('success', 'Verification request submitted.');
    }

    public function approve(Request $r, $id)
    {
        $req = VerificationRequest::findOrFail($id);

        $req->user->update([
            'upload_tier' => 'verified',
        ]);

        $req->update([
            'request_status' => 'approved',
            'reviewed_by' => $r->user()?->id ?? auth()->id(),
        ]);

        return $r->wantsJson()
            ? $this->apiData([
                'verification_request' => $req->fresh('user', 'reviewer'),
            ], 'Verification request approved.')
            : back()->with('success', 'Verification request approved.');
    }

    public function reject(Request $r, $id)
    {
        $req = VerificationRequest::findOrFail($id);

        $req->update([
            'request_status' => 'rejected',
            'reviewed_by' => $r->user()?->id ?? auth()->id(),
        ]);

        return $r->wantsJson()
            ? $this->apiData([
                'verification_request' => $req->fresh('user', 'reviewer'),
            ], 'Verification request rejected.')
            : back()->with('success', 'Verification request rejected.');
    }

    private function verificationEligibility($user): array
    {
        $rules = config('web3dshare.verification');
        $rules['min_total_downloads'] = $rules['min_total_downloads'] ?? $rules['min_downloads_per_model'] ?? 1;
        $reasons = [];

        $accountAgeDays = $user->created_at ? (int) floor($user->created_at->diffInDays(now())) : 0;
        if ($accountAgeDays < $rules['min_account_age_days']) {
            $reasons[] = 'Your account must be at least '.$rules['min_account_age_days'].' day(s) old.';
        }

        $models = Model3D::where('user_id', $user->id)
            ->select('id', 'title', 'download_count')
            ->get();

        if ($models->count() < $rules['min_models']) {
            $reasons[] = 'You need at least '.$rules['min_models'].' published model(s).';
        }

        $totalDownloadCount = (int) $models->sum('download_count');
        if ($totalDownloadCount < $rules['min_total_downloads']) {
            $reasons[] = 'You need at least '.$rules['min_total_downloads'].' counted download(s) across your published models.';
        }

        $latestRejectedRequest = VerificationRequest::where('user_id', $user->id)
            ->where('request_status', 'rejected')
            ->latest('updated_at')
            ->first();

        $cooldownUntil = null;
        if ($latestRejectedRequest) {
            $cooldownUntil = $latestRejectedRequest->updated_at->copy()->addHours($rules['rejection_cooldown_hours']);
            if (now()->lt($cooldownUntil)) {
                $reasons[] = 'Your last request was rejected. Please wait until '.$cooldownUntil->format('F j, Y H:i').' before trying again.';
            }
        }

        return [
            'eligible' => empty($reasons),
            'message' => implode(' ', $reasons),
            'reasons' => $reasons,
            'rules' => $rules,
            'model_count' => $models->count(),
            'total_download_count' => $totalDownloadCount,
            'account_age_days' => $accountAgeDays,
            'cooldown_until' => $cooldownUntil,
        ];
    }
}
