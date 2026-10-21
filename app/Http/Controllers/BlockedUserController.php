<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\PhotoAccessGrant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BlockedUserController extends Controller
{
    public function index(): View
    {
        $blockedUsers = Auth::user()->blockedUsers()
            ->with('profile.primaryPhoto')
            ->latest('blocked_users.created_at')
            ->get();

        return view('blocked-users.index', compact('blockedUsers'));
    }

    public function store(int $userId): RedirectResponse
    {
        $user = Auth::user();
        $targetUser = User::where('role', 'seeker')->findOrFail($userId);

        if ($targetUser->id === $user->id) {
            return back()->with('error', 'You cannot block your own profile.');
        }

        $user->blockedUsers()->syncWithoutDetaching([$targetUser->id]);
        PhotoAccessGrant::query()
            ->where(function ($query) use ($user, $targetUser): void {
                $query->where(function ($nested) use ($user, $targetUser): void {
                    $nested->whereHas('profile', fn ($profileQuery) => $profileQuery->where('user_id', $user->id))
                        ->where('granted_to_user_id', $targetUser->id);
                })->orWhere(function ($nested) use ($user, $targetUser): void {
                    $nested->whereHas('profile', fn ($profileQuery) => $profileQuery->where('user_id', $targetUser->id))
                        ->where('granted_to_user_id', $user->id);
                });
            })
            ->where('status', 'active')
            ->update(['status' => 'revoked']);
        AuditLog::record($user->id, 'user_blocked', 'User', $targetUser->id);

        return redirect()->route('discovery.index')->with('success', 'Profile blocked. You will no longer see this person in discovery or receive profile access to them.');
    }

    public function destroy(int $userId): RedirectResponse
    {
        $user = Auth::user();
        $user->blockedUsers()->detach($userId);
        AuditLog::record($user->id, 'user_unblocked', 'User', $userId);

        return back()->with('success', 'Profile unblocked.');
    }
}
