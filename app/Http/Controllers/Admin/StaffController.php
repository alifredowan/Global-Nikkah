<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStaffRequest;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $staffUsers = User::whereIn('role', ['super_admin', 'admin', 'moderator'])
            ->orderBy('created_at', 'desc')
            ->get();

        $availablePermissions = [
            'manage_verifications' => 'KYC Profile & ID Verifications',
            'manage_reports' => 'User Abuse Reports & Sanctions (Warn, Suspend, Ban)',
            'view_audit_logs' => 'Audit Logs & Governance Tracking',
            'manage_settings' => 'Platform Settings & Policies',
        ];

        return view('admin.staff', compact('staffUsers', 'availablePermissions'));
    }

    public function store(StoreStaffRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $permissions = $validated['role'] === 'super_admin'
            ? [
                'manage_verifications',
                'manage_reports',
                'view_audit_logs',
                'manage_settings',
            ]
            : ($validated['permissions'] ?? []);

        $staff = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'permissions' => $permissions,
            'password' => Hash::make($validated['password']),
            'gender' => 'male',
            'dob' => '1990-01-01',
            'marital_status' => 'never_married',
            'is_verified' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        AuditLog::record(Auth::id(), 'staff_member_created', 'User', $staff->id, [
            'role' => $staff->role,
            'permissions' => $staff->permissions,
        ]);

        return back()->with('success', "New staff member ({$staff->name} as ".ucwords(str_replace('_', ' ', $staff->role)).') created successfully!');
    }

    public function updatePermissions(Request $request, int $id): RedirectResponse
    {
        $staff = User::findOrFail($id);

        if ($staff->id === Auth::id()) {
            return back()->with('error', 'You cannot modify your own role or permissions.');
        }

        if ($staff->isSuperAdmin() && ! Auth::user()->isSuperAdmin()) {
            abort(403, 'Only a Super Admin can modify other Super Admins.');
        }

        $validated = $request->validate([
            'role' => ['nullable', 'in:super_admin,moderator'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:manage_verifications,manage_reports,view_audit_logs,manage_settings'],
        ]);

        $updates = [
            'permissions' => $validated['permissions'] ?? [],
        ];

        if (isset($validated['role'])) {
            $updates['role'] = $validated['role'];
            if ($validated['role'] === 'super_admin') {
                $updates['permissions'] = [
                    'manage_verifications',
                    'manage_reports',
                    'view_audit_logs',
                    'manage_settings',
                ];
            }
        }

        $staff->update($updates);

        AuditLog::record(Auth::id(), 'staff_permissions_updated', 'User', $staff->id, [
            'permissions' => $staff->permissions,
            'role' => $staff->role,
        ]);

        return back()->with('success', "Permissions updated for {$staff->name}.");
    }

    public function toggleStatus(int $id): RedirectResponse
    {
        $staff = User::findOrFail($id);

        if ($staff->id === Auth::id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $staff->update(['is_active' => ! $staff->is_active]);

        $statusText = $staff->is_active ? 'activated' : 'deactivated';

        AuditLog::record(Auth::id(), "staff_{$statusText}", 'User', $staff->id);

        return back()->with('success', "Staff account {$statusText} successfully.");
    }
}

