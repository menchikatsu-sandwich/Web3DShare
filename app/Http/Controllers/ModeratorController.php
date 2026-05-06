<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\VerificationRequest;
use Illuminate\Http\Request;

class ModeratorController extends Controller
{
    public function reports()
    {
        return Report::with('model3d')->latest()->get();
    }

    public function resolveReport(Request $r, Report $report)
    {
        $report->update([
            'report_status'=>'resolved',
            'reviewed_by'=>$r->user()->id
        ]);

        return response()->json(['msg'=>'done']);
    }

    public function verification()
    {
        return VerificationRequest::with('user')->get();
    }

    public function approve(Request $r, VerificationRequest $vr)
    {
        $vr->update([
            'request_status'=>'approved',
            'reviewed_by'=>$r->user()->id
        ]);

        $vr->user->update(['upload_tier'=>'verified']);

        return response()->json(['msg'=>'approved']);
    }
}