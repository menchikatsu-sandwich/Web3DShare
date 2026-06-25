<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Model3D;
use App\Models\Report;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $reports = Report::with(['model3d.user', 'reviewer'])
            ->where('reported_by', $request->user()->id)
            ->latest()
            ->get();
        $ownerReports = Report::with(['model3d.user', 'reporter', 'reviewer'])
            ->whereNotNull('owner_message')
            ->whereHas('model3d', fn ($query) => $query->withTrashed()->where('user_id', $request->user()->id))
            ->latest('owner_notified_at')
            ->latest()
            ->get();

        return view('reports.index', compact('reports', 'ownerReports'));
    }

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
