<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RoleName;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\Auth\RoleAssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private readonly RoleAssignmentService $roleAssignment
    ) {}

    public function index(Request $request): View
    {
        $users = User::with('role')
            ->when($request->search, fn($q, $s) =>
                $q->where('name', 'ilike', "%{$s}%")
                  ->orWhere('email', 'ilike', "%{$s}%")
            )
            ->when($request->role, fn($q, $r) =>
                $q->whereHas('role', fn($q2) => $q2->where('name', $r))
            )
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        $roles = Role::orderBy('name')->get();

        return view('pages.admin.users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        // Staff-creatable roles only — buyers register themselves
        $roles = Role::whereIn('name', array_map(
            fn(RoleName $r) => $r->value,
            RoleName::staffRoles()
        ))->orderBy('name')->get();

        return view('pages.admin.users.create', compact('roles'));
    }

    public function store(CreateUserRequest $request): RedirectResponse
    {
        $role = Role::where('name', $request->validated('role'))->firstOrFail();

        $user = User::create([
            'name'     => $request->validated('name'),
            'email'    => $request->validated('email'),
            'phone'    => $request->validated('phone'),
            'password' => bcrypt($request->validated('password')),
            'role_id'  => $role->id,
        ]);

        // Mark email as verified for staff created by admin
        $user->markEmailAsVerified();

        activity()
            ->causedBy($request->user())
            ->performedOn($user)
            ->withProperties(['role' => $role->name])
            ->log("Created staff account: {$user->email}");

        return redirect()->route('admin.users.index')
            ->with('success', "Account created for {$user->name}.");
    }

    public function show(User $user): View
    {
        $user->load('role');
        return view('pages.admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $roles = Role::whereIn('name', array_map(
            fn(RoleName $r) => $r->value,
            RoleName::staffRoles()
        ))->orderBy('name')->get();

        return view('pages.admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role'  => ['required', 'string', 'exists:roles,name'],
        ]);

        $role = Role::where('name', $validated['role'])->firstOrFail();

        $user->update([
            'name'    => $validated['name'],
            'phone'   => $validated['phone'],
            'role_id' => $role->id,
        ]);

        activity()
            ->causedBy($request->user())
            ->performedOn($user)
            ->withProperties(['new_role' => $role->name])
            ->log("Updated user account: {$user->email}");

        return redirect()->route('admin.users.index')
            ->with('success', "{$user->name} updated successfully.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        // Prevent self-deletion
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        activity()
            ->causedBy($request->user())
            ->performedOn($user)
            ->log("Soft-deleted user: {$user->email}");

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "{$user->name} has been removed.");
    }
}
