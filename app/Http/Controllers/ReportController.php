<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Model3D;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReportController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $r, Model3D $model)
    {
        if ($r->user()->cannot('create', [Report::class, $model])) {
            $message = 'You cannot report your own model.';

            return $r->wantsJson()
                ? $this->apiError($message, 403)
                : back()->with('error', $message);
        }

        $validator = Validator::make($r->all(), [
            'reason' => ['required', 'string', 'in:stolen_content,inappropriate_content,spam,broken_file,wrong_category,other'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $r->wantsJson()
                ? $this->apiError('Report validation failed.', 422, $validator->errors())
                : back()->withErrors($validator)->withInput();
        }

        $existingReport = Report::where('model_id', $model->id)
            ->where('reported_by', $r->user()->id)
            ->whereIn('report_status', ['pending', 'reviewed'])
            ->first();

        if ($existingReport) {
            $message = 'You already have an active report for this model.';

            return $r->wantsJson()
                ? $this->apiError($message, 409)
                : back()->with('error', $message);
        }

        $report = Report::create([
            'model_id' => $model->id,
            'reported_by' => $r->user()->id,
            'reason' => $r->reason,
            'description' => $r->description,
        ]);

        return $r->wantsJson()
            ? $this->apiData(['report' => $report], 'Report submitted.')
            : back()->with('success', 'Report submitted. A moderator will review it.');
    }
}
