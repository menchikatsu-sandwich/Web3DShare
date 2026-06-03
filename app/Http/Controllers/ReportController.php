<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Model3D;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function store(Request $r, Model3D $model)
    {
        if ($model->user_id === $r->user()->id) {
            $message = 'You cannot report your own model.';

            return $r->wantsJson()
                ? response()->json(['error' => $message], 403)
                : back()->with('error', $message);
        }

        $validator = Validator::make($r->all(), [
            'reason' => ['required', 'string', 'in:stolen_content,inappropriate_content,spam,broken_file,wrong_category,other'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $r->wantsJson()
                ? response()->json([
                    'message' => 'Report validation failed.',
                    'errors' => $validator->errors(),
                ], 422)
                : back()->withErrors($validator)->withInput();
        }

        $existingReport = Report::where('model_id', $model->id)
            ->where('reported_by', $r->user()->id)
            ->whereIn('report_status', ['pending', 'reviewed'])
            ->first();

        if ($existingReport) {
            $message = 'You already have an active report for this model.';

            return $r->wantsJson()
                ? response()->json(['error' => $message], 409)
                : back()->with('error', $message);
        }

        Report::create([
            'model_id' => $model->id,
            'reported_by' => $r->user()->id,
            'reason' => $r->reason,
            'description' => $r->description,
        ]);

        return $r->wantsJson()
            ? response()->json(['msg' => 'reported'])
            : back()->with('success', 'Report submitted. A moderator will review it.');
    }
}
