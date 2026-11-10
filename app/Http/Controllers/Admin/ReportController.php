<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $reports = Report::with(['reporter', 'reported', 'conversation', 'moderator'])
            ->latest()
            ->paginate(15);

        return view('admin.reports', compact('reports'));
    }

    public function action(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'action' => ['required', 'in:warn,suspend,ban,dismiss'],
            'resolution_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $admin = Auth::user();
        $report = Report::findOrFail($id);

        $action = $request->action;
        $reportedUser = $report->reported;

        if ($action === 'suspend' || $action === 'ban') {
            $reportedUser->update(['is_active' => false]);
        }

        $report->update([
            'status' => $action === 'dismiss' ? 'dismissed' : 'actioned',
            'action_taken' => $action,
            'resolution_notes' => $request->resolution_notes,
            'moderator_id' => $admin->id,
            'resolved_at' => now(),
        ]);

        AuditLog::record($admin->id, "report_{$action}", 'Report', $report->id, [
            'reported_user_id' => $reportedUser->id,
            'action' => $action,
        ]);

        return back()->with('success', "Report has been successfully processed with action: {$action}.");
    }
}

