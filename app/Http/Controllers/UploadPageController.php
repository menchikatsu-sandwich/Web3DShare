<?php

namespace App\Http\Controllers;

use App\Models\Model3D;
use App\Services\MetadataCache;
use Illuminate\Http\Request;

class UploadPageController extends Controller
{
    public function index(Request $request)
    {
        $categories = MetadataCache::categories();
        $user = $request->user();
        $monthlyLimit = config('web3dshare.limits.basic_monthly_uploads');
        $monthlyUploads = Model3D::where('user_id', $user->id)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $isVerifiedUploader = $user->isVerifiedUploader();
        $remainingUploads = $isVerifiedUploader
            ? null
            : max(0, $monthlyLimit - $monthlyUploads);
        $nextUploadReset = now()->addMonthNoOverflow()->startOfMonth();

        if ($request->wantsJson()) {
            return $this->apiData([
                'categories' => $categories,
                'upload_limit' => [
                    'monthly_limit' => $monthlyLimit,
                    'monthly_uploads' => $monthlyUploads,
                    'remaining_uploads' => $remainingUploads,
                    'is_verified_uploader' => $isVerifiedUploader,
                    'next_reset' => $nextUploadReset,
                ],
            ]);
        }

        return view('upload.index', compact(
            'categories',
            'monthlyLimit',
            'monthlyUploads',
            'remainingUploads',
            'isVerifiedUploader',
            'nextUploadReset'
        ));
    }
}
