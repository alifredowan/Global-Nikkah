<?php

namespace App\Http\Controllers;

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

        return redirect()->route('discovery.index')->with('success', 'Profile blocked. You will no longer see this person in discovery or receive profile access to them.');
    }

    public function destroy(int $userId): RedirectResponse
    {
        Auth::user()->blockedUsers()->detach($userId);

        return back()->with('success', 'Profile unblocked.');
    }
}
