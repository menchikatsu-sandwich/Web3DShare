<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Model3D;
use Illuminate\Http\Request;

class UploadPageController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $user = $request->user();
        $monthlyLimit = 5;
        $monthlyUploads = Model3D::where('user_id', $user->id)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $isVerifiedUploader = $user->isVerifiedUploader();
        $remainingUploads = $isVerifiedUploader
            ? null
            : max(0, $monthlyLimit - $monthlyUploads);
        $nextUploadReset = now()->addMonthNoOverflow()->startOfMonth();

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
