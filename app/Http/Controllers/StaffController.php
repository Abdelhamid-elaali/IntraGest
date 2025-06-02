<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    /**
     * Check if the user is authorized to access staff management.
     *
     * @return void
     */
    private function checkAuthorization()
    {
        if (!auth()->check() || (!auth()->user()->isDirector() && !auth()->user()->isSuperAdmin() && !auth()->user()->isAdmin())) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display a listing of staff members.
     */
    public function index()
    {
        $this->checkAuthorization();
        
        // Get staff roles (boarding manager and stock manager)
        $staffRoles = Role::whereIn('slug', ['manager', 'boarding-manager', 'stock-manager'])->pluck('id');
        
        // Get all users with these roles
        $staffMembers = User::whereHas('roles', function($query) use ($staffRoles) {
            $query->whereIn('role_id', $staffRoles);
        })->with('roles')->get();
        
        return view('staff.index', compact('staffMembers'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $this->checkAuthorization();
        
        $roles = Role::whereIn('slug', ['manager', 'boarding-manager', 'stock-manager'])->get();
        return view('staff.create', compact('roles'));
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(Request $request)
    {
        $this->checkAuthorization();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'role_id' => 'required|exists:roles,id'
        ]);

        DB::beginTransaction();
        try {
            // Create the user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
            ]);

            // Assign the role
            $role = Role::findOrFail($validated['role_id']);
            $role->assignToUser($user);

            DB::commit();
            return redirect()->route('staff.index')->with('success', 'Staff member created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create staff member: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(User $staff)
    {
        $this->checkAuthorization();
        
        $roles = Role::whereIn('slug', ['manager', 'boarding-manager', 'stock-manager'])->get();
        return view('staff.edit', compact('staff', 'roles'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(Request $request, User $staff)
    {
        $this->checkAuthorization();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($staff->id),
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            // Update user details
            $staff->name = $validated['name'];
            $staff->email = $validated['email'];
            $staff->phone = $validated['phone'] ?? null;
            $staff->address = $validated['address'] ?? null;
            $staff->city = $validated['city'] ?? null;
            
            // Update password if provided
            if (!empty($validated['password'])) {
                $staff->password = Hash::make($validated['password']);
            }
            
            $staff->save();

            // Update role
            $staff->roles()->detach();
            $role = Role::findOrFail($validated['role_id']);
            $role->assignToUser($staff);

            DB::commit();
            return redirect()->route('staff.index')->with('success', 'Staff member updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update staff member: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified staff member.
     */
    public function show(User $staff)
    {
        $this->checkAuthorization();
        
        return view('staff.show', compact('staff'));
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(User $staff)
    {
        $this->checkAuthorization();
        
        try {
            // Remove all roles
            $staff->roles()->detach();
            
            // Delete the user
            $staff->delete();
            
            return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete staff member: ' . $e->getMessage());
        }
    }
}
