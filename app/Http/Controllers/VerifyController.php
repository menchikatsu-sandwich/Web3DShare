<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerificationRequest;

class VerifyController extends Controller
{
    public function index()
    {
        return view('verify.index');
    }

    public function store(Request $r)
    {
        VerificationRequest::create([
            'user_id'=>$r->user()->id,
            'note'=>$r->note,
            'request_status'=>'pending'
        ]);

        return back();
    }

    public function approve($id)
    {
        $req = \App\Models\VerificationRequest::findOrFail($id);

        $req->user->update([
            'upload_tier'=>'verified'
        ]);

        $req->delete();

        return back();
    }

    public function reject($id)
    {
        \App\Models\VerificationRequest::findOrFail($id)->delete();
        return back();
    }
}