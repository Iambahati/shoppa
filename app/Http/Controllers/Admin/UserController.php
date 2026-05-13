<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RoleName;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminCreateUserRequest;
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
            ->when($request->search, fn($q) =>
                $q->where('name', 'ilike', "%{$request->search}%")
                  ->orWhere('email', 'ilike', "%{$request->search}%")
            )
            ->when($request->role, fn($q) =>
                $q->whereHas('role', fn($r) => $r->where('name', $request->role))
            )
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $roles = Role::orderBy('name')->get();

        return view('pages.admin.users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        // Only staff roles are available here — buyers self-register
        $roles = Role::whereIn('name', collect(RoleName::staffRoles())->map->value->all())
            ->orderBy('name')
            ->get();

        return view('pages.admin.users.create', compact('roles'));
    }

    public function store(AdminCreateUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name'     => $request->validated('name'),
            'email'    => $request->validated('email'),
            'phone'    => $request->validated('phone'),
            'password' => bcrypt($request->validated('password')),
        ]);

        $roleName = RoleName::from($request->validated('role'));
        $this->roleAssignment->assignRole($user, $roleName);

        activity()
            ->causedBy($request->user())
            ->performedOn($user)
            ->withProperties(['role' => $roleName->value])
            ->log("Staff account created");

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
        $roles = Role::whereIn('name', collect(RoleName::staffRoles())->map->value->all())
            ->orderBy('name')
            ->get();

        return view('pages.admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role'  => ['required', 'string', 'in:' . collect(RoleName::staffRoles())->map->value->implode(',')],
        ]);

        $user->update([
            'name'  => $validated['name'],
            'phone' => $validated['phone'],
        ]);

        $this->roleAssignment->assignRole($user, RoleName::from($validated['role']));

        activity()
            ->causedBy($request->user())
            ->performedOn($user)
            ->withProperties(['new_role' => $validated['role']])
            ->log("Staff account updated");

        return redirect()->route('admin.users.index')
            ->with('success', "Account updated.");
    }
}