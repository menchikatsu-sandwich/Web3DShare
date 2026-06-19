<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerificationRequest;
use App\Models\Model3D;

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
            return response()->json([
                'pending_request' => $pendingRequest,
                'verification_check' => $verificationCheck,
            ]);
        }

        return view('verify.index', compact('pendingRequest', 'verificationCheck'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'note' => ['required', 'string', 'max:2000'],
        ]);

        $verificationCheck = $this->verificationEligibility($r->user());
        if (!$verificationCheck['eligible']) {
            return $r->wantsJson()
                ? response()->json([
                    'error' => $verificationCheck['message'],
                    'verification_check' => $verificationCheck,
                ], 403)
                : back()->with('error', $verificationCheck['message']);
        }

        $existing = VerificationRequest::where('user_id', $r->user()->id)
            ->where('request_status', 'pending')
            ->first();

        if ($existing) {
            return $r->wantsJson()
                ? response()->json(['error' => 'You already have a pending verification request.'], 409)
                : back()->with('error', 'You already have a pending verification request.');
        }

        $verificationRequest = VerificationRequest::create([
            'user_id'=>$r->user()->id,
            'note'=>$r->note,
            'request_status'=>'pending'
        ]);

        return $r->wantsJson()
            ? response()->json([
                'message' => 'Verification request submitted.',
                'verification_request' => $verificationRequest,
            ], 201)
            : back()->with('success', 'Verification request submitted.');
    }

    public function approve(Request $r, $id)
    {
        $req = \App\Models\VerificationRequest::findOrFail($id);

        $req->user->update([
            'upload_tier'=>'verified'
        ]);

        $req->update([
            'request_status' => 'approved',
            'reviewed_by' => $r->user()?->id ?? auth()->id(),
        ]);

        return $r->wantsJson()
            ? response()->json([
                'message' => 'Verification request approved.',
                'verification_request' => $req->fresh('user', 'reviewer'),
            ])
            : back()->with('success', 'Verification request approved.');
    }

    public function reject(Request $r, $id)
    {
        $req = \App\Models\VerificationRequest::findOrFail($id);

        $req->update([
            'request_status' => 'rejected',
            'reviewed_by' => $r->user()?->id ?? auth()->id(),
        ]);

        return $r->wantsJson()
            ? response()->json([
                'message' => 'Verification request rejected.',
                'verification_request' => $req->fresh('user', 'reviewer'),
            ])
            : back()->with('success', 'Verification request rejected.');
    }

    private function verificationEligibility($user): array
    {
        $rules = config('web3dshare.verification');
        $reasons = [];

        $accountAgeDays = $user->created_at ? (int) floor($user->created_at->diffInDays(now())) : 0;
        if ($accountAgeDays < $rules['min_account_age_days']) {
            $reasons[] = 'Your account must be at least ' . $rules['min_account_age_days'] . ' day(s) old.';
        }

        $models = Model3D::where('user_id', $user->id)
            ->select('id', 'title', 'download_count')
            ->get();

        if ($models->count() < $rules['min_models']) {
            $reasons[] = 'You need at least ' . $rules['min_models'] . ' published model(s).';
        }

        $modelsBelowDownloadRule = $models
            ->filter(fn($model) => $model->download_count < $rules['min_downloads_per_model'])
            ->values();

        if ($models->isNotEmpty() && $modelsBelowDownloadRule->isNotEmpty()) {
            $reasons[] = 'Every model must have at least ' . $rules['min_downloads_per_model'] . ' counted download(s).';
        }

        $latestRejectedRequest = VerificationRequest::where('user_id', $user->id)
            ->where('request_status', 'rejected')
            ->latest('updated_at')
            ->first();

        $cooldownUntil = null;
        if ($latestRejectedRequest) {
            $cooldownUntil = $latestRejectedRequest->updated_at->copy()->addHours($rules['rejection_cooldown_hours']);
            if (now()->lt($cooldownUntil)) {
                $reasons[] = 'Your last request was rejected. Please wait until ' . $cooldownUntil->format('F j, Y H:i') . ' before trying again.';
            }
        }

        return [
            'eligible' => empty($reasons),
            'message' => implode(' ', $reasons),
            'reasons' => $reasons,
            'rules' => $rules,
            'model_count' => $models->count(),
            'models_below_download_rule' => $modelsBelowDownloadRule,
            'account_age_days' => $accountAgeDays,
            'cooldown_until' => $cooldownUntil,
        ];
    }
}
