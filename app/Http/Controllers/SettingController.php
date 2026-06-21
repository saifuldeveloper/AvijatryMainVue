<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage users', only: ['users', 'userStore', 'userUpdate', 'userDelete', 'userRestore', 'userForceDelete']),
            new Middleware('permission:manage roles', only: ['roles', 'roleStore', 'roleUpdate', 'roleDelete']),
        ];
    }

    /**
     * Display users list page.
     */
    public function users(): Response
    {
        $users = User::with('roles')->latest()->get();
        $roles = Role::latest()->get();
        $trashUsers = User::onlyTrashed()->with('roles')->latest()->get();
        
        return Inertia::render('Setting/Users', [
            'users' => $users,
            'roles' => $roles,
            'trashUsers' => $trashUsers,
        ]);
    }

    /**
     * Store a new user.
     */
    public function userStore(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password, // automatically hashed if password cast is used
            'created_by' => Auth::id(),
        ]);

        $user->assignRole($request->role);

        logActivity($user, $user->id, 'setting.users', 'Create', __('New user created'));

        return Redirect::back()->with('success', __('New user created successfully.'));
    }

    /**
     * Update user details and roles.
     */
    public function userUpdate(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
            'role' => 'required|string|exists:roles,name',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $userData['password'] = $request->password;
        }

        $user->update($userData);
        $user->syncRoles($request->role);

        logActivity($user, $user->id, 'setting.users', 'Update', __('User edited'));

        return Redirect::back()->with('success', __('User updated successfully.'));
    }

    /**
     * Soft delete user.
     */
    public function userDelete(User $user): RedirectResponse
    {
        // Don't let users delete themselves
        if ($user->id === Auth::id()) {
            return Redirect::back()->with('error', __('You cannot delete your own account.'));
        }

        $user->delete();

        logActivity($user, $user->id, '', 'SoftDelete', __('User deleted'));

        return Redirect::back()->with('success', __('User soft deleted successfully.'));
    }

    /**
     * Restore soft deleted user.
     */
    public function userRestore(int $id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        logActivity($user, $user->id, '', 'Restore', __('User restored'));

        return Redirect::back()->with('success', __('User restored successfully.'));
    }

    /**
     * Permanently delete user.
     */
    public function userForceDelete(int $id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        
        // Don't let users delete themselves
        if ($user->id === Auth::id()) {
            return Redirect::back()->with('error', __('You cannot delete your own account.'));
        }

        $user->forceDelete();

        logActivity($user, $user->id, '', 'Delete', __('User permanently deleted'));

        return Redirect::back()->with('success', __('User permanently deleted.'));
    }

    /**
     * Display roles & permissions page.
     */
    public function roles(): Response
    {
        $roles = Role::with('permissions')->latest()->get();
        $permissions = Permission::all();

        return Inertia::render('Setting/Roles', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store new role.
     */
    public function roleStore(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        logActivity($role, $role->id, 'setting.roles', 'Create', __('New user type created'));

        return Redirect::back()->with('success', __('New user type created successfully.'));
    }

    /**
     * Update role permission mapping.
     */
    public function roleUpdate(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        logActivity($role, $role->id, 'setting.roles', 'Update', __('User type edited'));

        return Redirect::back()->with('success', __('User type updated successfully.'));
    }

    /**
     * Delete role.
     */
    public function roleDelete(Role $role): RedirectResponse
    {
        // Check if role is assigned to active or trashed users
        if ($role->users()->count() > 0) {
            return Redirect::back()->with('error', __('This user type is associated with a user and cannot be deleted.'));
        }

        logActivity($role, $role->id, 'setting.roles', 'Delete', __('User type deleted'));

        $role->delete();

        return Redirect::back()->with('success', __('User type deleted successfully.'));
    }
}
