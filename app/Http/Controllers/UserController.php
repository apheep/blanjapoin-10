<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of all users with statistics
     */
    public function index()
    {
        // Only admin with can_approve = 1 can access
        if (!Auth::check() || !Auth::user()->can_approve) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $users = User::all();
        $totalUsers = $users->count();
        $adminCount = $users->where('role', 'admin')->count();
        $adminActiveCount = $users->where('role', 'admin')->where('can_approve', 1)->count();
        $userActiveCount = $users->where('role', 'user')->count();

        return view('usermng', compact('users', 'totalUsers', 'adminCount', 'adminActiveCount', 'userActiveCount'));
    }

    /**
     * Get all users with search and filter
     */
    public function getUsers(Request $request)
    {
        // Only admin with can_approve = 1 can access
        if (!Auth::check() || !Auth::user()->can_approve) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = User::query();

        // Search by username
        if ($request->has('search') && !empty($request->search)) {
            $query->where('username', 'like', '%' . $request->search . '%');
        }

        // Filter by role
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }

        // Filter by can_approve
        if ($request->has('can_approve') && $request->can_approve !== '') {
            $query->where('can_approve', (int) $request->can_approve);
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $users = $query->paginate($perPage);

        return response()->json($users);
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        // Only admin with can_approve = 1 can access
        if (!Auth::check() || !Auth::user()->can_approve) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'username' => 'required|unique:users|min:3|max:50',
            'password' => 'required|min:6|max:100',
            'role' => 'required|in:admin,user',
            'can_approve' => 'boolean',
        ]);

        try {
            $user = User::create([
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'can_approve' => $request->boolean('can_approve') ? 1 : 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dibuat',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal membuat user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update user's can_approve status
     */
    public function toggleApproval(Request $request, User $user)
    {
        // Only admin with can_approve = 1 can access
        if (!Auth::check() || !Auth::user()->can_approve) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Prevent current user from removing their own approval
        if (Auth::id() === $user->id) {
            return response()->json([
                'error' => 'Tidak dapat mengubah approval status diri sendiri',
            ], 400);
        }

        try {
            $user->can_approve = $user->can_approve ? 0 : 1;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Status approval berhasil diperbarui',
                'can_approve' => $user->can_approve,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal memperbarui status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a user
     */
    public function destroy(Request $request, User $user)
    {
        // Only admin with can_approve = 1 can access
        if (!Auth::check() || !Auth::user()->can_approve) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Prevent current user from deleting themselves
        if (Auth::id() === $user->id) {
            return response()->json([
                'error' => 'Tidak dapat menghapus akun Anda sendiri',
            ], 400);
        }

        try {
            $username = $user->username;
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => "User '$username' berhasil dihapus",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk delete users
     */
    public function bulkDelete(Request $request)
    {
        // Only admin with can_approve = 1 can access
        if (!Auth::check() || !Auth::user()->can_approve) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:users,id',
        ]);

        try {
            // Prevent deleting current user
            if (in_array(Auth::id(), $validated['ids'])) {
                return response()->json([
                    'error' => 'Tidak dapat menghapus akun Anda sendiri',
                ], 400);
            }

            User::whereIn('id', $validated['ids'])->delete();

            return response()->json([
                'success' => true,
                'message' => count($validated['ids']) . ' user berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk toggle approval status
     */
    public function bulkToggleApproval(Request $request)
    {
        // Only admin with can_approve = 1 can access
        if (!Auth::check() || !Auth::user()->can_approve) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:users,id',
            'status' => 'required|boolean',
        ]);

        try {
            User::whereIn('id', $validated['ids'])->update([
                'can_approve' => $validated['status'] ? 1 : 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status approval berhasil diperbarui untuk ' . count($validated['ids']) . ' user',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal memperbarui status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
