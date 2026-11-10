<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\VerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class VerificationController extends Controller
{
    public function index(): View
    {
        $verifications = VerificationRequest::with(['user.profile', 'reviewer'])
            ->latest()
            ->paginate(15);

        return view('admin.verifications', compact('verifications'));
    }

    public function downloadDocument(int $id, string $type): Response
    {
        if (! in_array($type, ['document', 'selfie'], true)) {
            abort(404);
        }

        $verification = VerificationRequest::findOrFail($id);
        $path = $type === 'document' ? $verification->document_path : $verification->selfie_path;

        if (! $path || ! Storage::disk('local')->exists($path)) {
            abort(404);
        }

        AuditLog::record(Auth::id(), 'verification_document_downloaded', 'VerificationRequest', $id, [
            'document_type' => $type,
        ]);

        $extension = pathinfo($path, PATHINFO_EXTENSION) ?: 'bin';
        $fullPath = Storage::disk('local')->path($path);
        $filename = "verification-{$id}-{$type}.{$extension}";
        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';

        return response(file_get_contents($fullPath), 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'private, no-store',
        ]);
    }

    public function approve(int $id): RedirectResponse
    {
        $admin = Auth::user();
        $request = VerificationRequest::whereKey($id)
            ->where('status', 'pending')
            ->firstOrFail();

        $request->update([
            'status' => 'approved',
            'reviewer_id' => $admin->id,
            'reviewed_at' => now(),
        ]);

        $request->user->update(['is_verified' => true]);

        AuditLog::record($admin->id, 'verification_approved', 'User', $request->user_id);

        return back()->with('success', 'Verification approved! Candidate now has the official "Verified" badge (FR-1.4).');
    }

    public function reject(Request $request, int $id): RedirectResponse
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);

        $admin = Auth::user();
        $verification = VerificationRequest::whereKey($id)
            ->where('status', 'pending')
            ->firstOrFail();

        $verification->update([
            'status' => 'rejected',
            'reviewer_id' => $admin->id,
            'rejection_reason' => $request->rejection_reason,
            'reviewed_at' => now(),
        ]);

        $verification->user->update(['is_verified' => false]);

        AuditLog::record($admin->id, 'verification_rejected', 'User', $verification->user_id, [
            'reason' => $request->rejection_reason,
        ]);

        return back()->with('info', 'Verification rejected with reason recorded.');
    }
}

