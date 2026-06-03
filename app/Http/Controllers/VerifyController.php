<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerificationRequest;

class VerifyController extends Controller
{
    public function index(Request $r)
    {
        $pendingRequest = VerificationRequest::where('user_id', $r->user()->id)
            ->where('request_status', 'pending')
            ->latest()
            ->first();

        return view('verify.index', compact('pendingRequest'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'note' => ['required', 'string', 'max:2000'],
        ]);

        $existing = VerificationRequest::where('user_id', $r->user()->id)
            ->where('request_status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a pending verification request.');
        }

        VerificationRequest::create([
            'user_id'=>$r->user()->id,
            'note'=>$r->note,
            'request_status'=>'pending'
        ]);

        return back()->with('success', 'Verification request submitted.');
    }

    public function approve($id)
    {
        $req = \App\Models\VerificationRequest::findOrFail($id);

        $req->user->update([
            'upload_tier'=>'verified'
        ]);

        $req->delete();

        return back()->with('success', 'Verification request approved.');
    }

    public function reject($id)
    {
        \App\Models\VerificationRequest::findOrFail($id)->delete();
        return back()->with('success', 'Verification request rejected.');
    }
}
