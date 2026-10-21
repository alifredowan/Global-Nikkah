<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\User;
use App\Services\QuotaService;
use App\Services\WaliWorkflowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InterestApiController extends Controller
{
    public function __construct(
        protected QuotaService $quotaService,
        protected WaliWorkflowService $waliService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $received = Interest::with('sender.profile.primaryPhoto')
            ->where('recipient_id', $user->id)
            ->latest()
            ->get();

        $sent = Interest::with('recipient.profile.primaryPhoto')
            ->where('sender_id', $user->id)
            ->latest()
            ->get();

        $received = $received->reject(
            fn (Interest $interest): bool => $user->hasBlockedRelationshipWith($interest->sender)
        )->values();
        $sent = $sent->reject(
            fn (Interest $interest): bool => $user->hasBlockedRelationshipWith($interest->recipient)
        )->values();

        return response()->json([
            'status' => 'success',
            'data' => [
                'received' => $received,
                'sent' => $sent,
            ],
        ]);
    }

    public function store(Request $request, int $recipientId): JsonResponse
    {
        $request->validate([
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        if ($user->id === $recipientId) {
            return response()->json(['status' => 'error', 'message' => 'Cannot send interest to yourself'], 422);
        }

        $recipient = User::with('profile')->findOrFail($recipientId);

        if ($recipient->role !== 'seeker' || ! $recipient->is_active) {
            return response()->json(['status' => 'error', 'message' => 'This user is not accepting matrimonial interest requests.'], 422);
        }

        if ($user->hasBlockedRelationshipWith($recipient)) {
            return response()->json(['status' => 'error', 'message' => 'This interaction is unavailable because one of the profiles has been blocked.'], 403);
        }

        if ($user->isMarried() || $recipient->isMarried()) {
            return response()->json(['status' => 'error', 'message' => 'Married profiles cannot exchange new interest requests.'], 422);
        }

        if ($user->gender && $recipient->gender && $user->gender === $recipient->gender) {
            return response()->json(['status' => 'error', 'message' => 'Interest requests can only be sent to candidates of the opposite gender.'], 422);
        }

        if (! $this->quotaService->canSendInterest($user)) {
            return response()->json(['status' => 'error', 'message' => 'Daily interest quota exceeded'], 429);
        }

        $requiresWali = $this->waliService->requiresWaliApproval($recipient);

        if (Interest::where('sender_id', $user->id)->where('recipient_id', $recipient->id)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'You have already sent an interest request to this candidate.'], 409);
        }

        $interest = Interest::firstOrCreate([
            'sender_id' => $user->id,
            'recipient_id' => $recipient->id,
        ], [
            'status' => 'pending',
            'wali_approval_status' => $requiresWali ? 'pending' : 'not_required',
            'message_note' => $request->input('note'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Interest sent successfully',
            'data' => $interest,
        ], 201);
    }

    public function respond(Request $request, int $interestId): JsonResponse
    {
        $request->validate(['action' => 'required|in:accept,decline,ignore']);
        $user = $request->user();

        $interest = Interest::where('recipient_id', $user->id)->findOrFail($interestId);

        if ($user->hasBlockedRelationshipWith($interest->sender)) {
            return response()->json(['status' => 'error', 'message' => 'This interaction is unavailable because one of the profiles has been blocked.'], 403);
        }

        if ($interest->status !== 'pending') {
            return response()->json(['status' => 'error', 'message' => "This interest request has already been {$interest->status}."], 409);
        }

        if ($request->action === 'accept') {
            $interest->update(['status' => 'accepted', 'responded_at' => now()]);
            $unlocked = false;
            if ($interest->wali_approval_status !== 'pending') {
                $this->waliService->createOrUnlockConversation($interest);
                $unlocked = true;
            }

            return response()->json([
                'status' => 'success',
                'message' => $unlocked ? 'Interest accepted and chat unlocked' : 'Interest accepted, awaiting wali approval',
                'chat_unlocked' => $unlocked,
            ]);
        }

        $interest->update(['status' => $request->action, 'responded_at' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Interest updated',
        ]);
    }
}
