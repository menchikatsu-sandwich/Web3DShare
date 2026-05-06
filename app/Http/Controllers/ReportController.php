<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Model3D;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $r, Model3D $model)
    {
        $r->validate([
            'reason'=>'required'
        ]);

        Report::create([
            'model_id'=>$model->id,
            'reported_by'=>$r->user()->id,
            'reason'=>$r->reason,
            'description'=>$r->description
        ]);

        return $r->wantsJson()
            ? response()->json(['msg'=>'reported'])
            : back();
    }
}