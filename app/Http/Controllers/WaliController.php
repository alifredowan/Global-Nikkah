<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Conversation;
use App\Models\Interest;
use App\Models\User;
use App\Models\WaliLink;
use App\Services\WaliWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WaliController extends Controller
{
    public function __construct(
        protected WaliWorkflowService $waliService
    ) {}

    public function dashboard(): View
    {
        $user = Auth::user();

        // If current user is a Wali
        $wards = WaliLink::with(['seeker.profile.primaryPhoto'])
            ->where('wali_user_id', $user->id)
            ->orWhere('wali_email', $user->email)
            ->get();

        $wardUserIds = $wards->pluck('seeker_user_id')->toArray();

        $pendingInterests = Interest::with(['sender.profile', 'recipient'])
            ->whereIn('recipient_id', $wardUserIds)
            ->where('wali_approval_status', 'pending')
            ->latest()
            ->get();

        $chaperonedConversations = Conversation::whereHas('participants', function ($q) use ($user) {
            $q->where('conversation_participants.user_id', $user->id)
                ->where('conversation_participants.role', 'wali_chaperone');
        })->with(['participants', 'latestMessage'])->latest()->get();

        return view('wali.dashboard', compact('wards', 'pendingInterests', 'chaperonedConversations'));
    }

    public function linkForm(): View
    {
        $user = Auth::user();
        $existingLinks = WaliLink::with('wali')->where('seeker_user_id', $user->id)->get();

        return view('wali.link', compact('user', 'existingLinks'));
    }

    public function linkStore(Request $request): RedirectResponse
    {
        $request->validate([
            'relationship_type' => ['required', 'string', 'in:father,brother,uncle,grandfather,other_mahram'],
            'permission_level' => ['required', 'in:view_only,approve_required,full_proxy'],
            'wali_name' => ['required', 'string', 'max:100'],
            'wali_email' => ['nullable', 'email'],
            'wali_phone' => ['nullable', 'string', 'max:20'],
        ]);

        $seeker = Auth::user();

        $this->waliService->linkWali(
            $seeker,
            $request->relationship_type,
            $request->permission_level,
            $request->wali_name,
            $request->wali_email,
            $request->wali_phone
        );

        $seeker->profile?->update(['wali_required' => true]);

        AuditLog::record($seeker->id, 'wali_linked', 'WaliLink', null, [
            'relationship' => $request->relationship_type,
            'permission' => $request->permission_level,
        ]);

        return back()->with('success', 'Guardian (Wali) link configured! Communication and interest requests will follow your chosen guardian permission settings (FR-4.3).');
    }

    public function approveInterest(int $interestId): RedirectResponse
    {
        $wali = Auth::user();
        $interest = Interest::findOrFail($interestId);

        $success = $this->waliService->approveByWali($interest, $wali);

        if (! $success) {
            return back()->with('error', 'You are not authorized to approve this interest request.');
        }

        AuditLog::record($wali->id, 'wali_approved_interest', 'Interest', $interest->id);

        return back()->with('success', 'Interest request approved by Guardian! The conversation is now unlocked for halal discussion.');
    }

    public function rejectInterest(int $interestId): RedirectResponse
    {
        $wali = Auth::user();
        $interest = Interest::findOrFail($interestId);

        $this->waliService->rejectByWali($interest, $wali);

        AuditLog::record($wali->id, 'wali_rejected_interest', 'Interest', $interest->id);

        return back()->with('info', 'Interest request declined by Guardian.');
    }

    /**
     * Show guardian invitation page via email link.
     */
    public function showInvitation(string $token): View
    {
        $link = WaliLink::with(['seeker.profile.primaryPhoto'])
            ->where('invite_token', $token)
            ->firstOrFail();

        return view('wali.invitation', compact('link'));
    }

    /**
     * Accept guardian invitation.
     */
    public function acceptInvitation(Request $request, string $token): RedirectResponse
    {
        $link = WaliLink::with('seeker')->where('invite_token', $token)->firstOrFail();

        if ($link->status === 'active') {
            if (Auth::check()) {
                return redirect()->route('wali.dashboard')->with('info', 'This Guardian connection is already active.');
            }

            return redirect()->route('login')->with('info', 'This Guardian connection is already active. Please sign in to your dashboard.');
        }

        $currentUser = Auth::user();

        // Link user ID directly if logged in
        $this->waliService->approveWaliLink($link, $currentUser);

        AuditLog::record(
            $currentUser?->id ?? $link->seeker_user_id,
            'wali_invitation_accepted',
            'WaliLink',
            $link->id,
            ['email' => $link->wali_email]
        );

        if ($currentUser) {
            return redirect()->route('wali.dashboard')->with('success', "Alhamdulillah! You have successfully confirmed guardianship for {$link->seeker->name}.");
        }

        return redirect()->route('login')->with('success', "Alhamdulillah! You have confirmed guardianship for {$link->seeker->name}. Please sign in to chaperone and supervise discussions.");
    }

    /**
     * Decline guardian invitation.
     */
    public function declineInvitation(Request $request, string $token): RedirectResponse
    {
        $link = WaliLink::where('invite_token', $token)->firstOrFail();

        $this->waliService->declineWaliLink($link);

        AuditLog::record(
            Auth::id() ?? $link->seeker_user_id,
            'wali_invitation_declined',
            'WaliLink',
            $link->id,
            ['email' => $link->wali_email]
        );

        return redirect()->route('landing')->with('info', 'Guardian connection request has been declined.');
    }
}
