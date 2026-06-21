<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Model3D;
use App\Models\Report;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReportController extends Controller
{
    use AuthorizesRequests;

    public function store(StoreReportRequest $request, Model3D $model)
    {
        if ($request->user()->cannot('create', [Report::class, $model])) {
            $message = 'You cannot report your own model.';

            return $request->wantsJson()
                ? $this->apiError($message, 403)
                : back()->with('error', $message);
        }

        $data = $request->validated();

        $existingReport = Report::where('model_id', $model->id)
            ->where('reported_by', $request->user()->id)
            ->whereIn('report_status', ['pending', 'reviewed'])
            ->first();

        if ($existingReport) {
            $message = 'You already have an active report for this model.';

            return $request->wantsJson()
                ? $this->apiError($message, 409)
                : back()->with('error', $message);
        }

        $report = Report::create([
            'model_id' => $model->id,
            'reported_by' => $request->user()->id,
            'reason' => $data['reason'],
            'description' => $data['description'] ?? null,
        ]);

        return $request->wantsJson()
            ? $this->apiData(['report' => $report], 'Report submitted.')
            : back()->with('success', 'Report submitted. A moderator will review it.');
    }
}
