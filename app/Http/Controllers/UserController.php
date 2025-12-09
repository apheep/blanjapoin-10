<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserController extends Controller
{
    /**
     * Display a listing of all users with statistics
     */
    public function index(Request $request)
    {
        // Only admin with can_approve = 1 can access
        if (!Auth::check() || !Auth::user()->can_approve) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Get all users for KPI calculations (unfiltered)
        $allUsers = User::all();
        $totalUsers = $allUsers->count();
        $adminCount = $allUsers->where('role', 'admin')->count();
        $adminActiveCount = $allUsers->where('role', 'admin')->where('can_approve', 1)->count();
        $userActiveCount = $allUsers->where('role', 'user')->count();

        // Build query for filtered/paginated users
        $query = User::query();

        // Search by username
        if ($request->has('search') && !empty($request->search)) {
            $query->where('username', 'like', '%' . $request->search . '%');
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $users = $query->orderBy('id', 'desc')->paginate($perPage)->appends($request->query());

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
            'no_hp' => 'nullable|string|max:20',
            'username' => 'required|unique:users|min:3|max:50',
            'email' => 'nullable|email|max:100',
            'password' => 'required|min:6|max:100',
            'role' => 'required|in:admin,user',
            'can_approve' => 'boolean',
        ]);

        try {
            $user = User::create([
                'no_hp' => $validated['no_hp'] ?? null,
                'username' => $validated['username'],
                'email' => $validated['email'] ?? null,
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

    /**
     * Preview Excel file before import
     */
    public function importPreview(Request $request)
    {
        // Only admin with can_approve = 1 can access
        if (!Auth::check() || !Auth::user()->can_approve) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240', // Max 10MB
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            if (empty($rows)) {
                return response()->json(['error' => 'File Excel kosong atau tidak valid'], 400);
            }

            if (count($rows) < 2) {
                return response()->json(['error' => 'File Excel harus memiliki minimal 1 baris data (selain header)'], 400);
            }

            // First row is header
            $headers = array_map('strtolower', array_map('trim', $rows[0]));
            
            // Validate required headers
            $requiredHeaders = ['username', 'password', 'role'];
            $missingHeaders = [];
            foreach ($requiredHeaders as $req) {
                if (!in_array($req, $headers)) {
                    $missingHeaders[] = $req;
                }
            }

            if (!empty($missingHeaders)) {
                return response()->json([
                    'error' => 'Header yang wajib tidak ditemukan: ' . implode(', ', $missingHeaders)
                ], 400);
            }

            // Get data rows (skip header)
            $dataRows = array_slice($rows, 1);
            $previewData = [];

            foreach ($dataRows as $row) {
                $rowData = [];
                foreach ($headers as $index => $header) {
                    $rowData[$header] = $row[$index] ?? '';
                }
                $previewData[] = $rowData;
            }

            return response()->json([
                'success' => true,
                'headers' => $rows[0], // Original headers for display
                'data' => $previewData,
                'total_rows' => count($previewData)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal membaca file Excel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import users from Excel file
     */
    public function import(Request $request)
    {
        // Only admin with can_approve = 1 can access
        if (!Auth::check() || !Auth::user()->can_approve) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240', // Max 10MB
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            if (empty($rows)) {
                return response()->json(['error' => 'File Excel kosong atau tidak valid'], 400);
            }

            if (count($rows) < 2) {
                return response()->json(['error' => 'File Excel harus memiliki minimal 1 baris data (selain header)'], 400);
            }

            // First row is header - normalize to lowercase
            $headers = array_map('strtolower', array_map('trim', $rows[0]));
            
            // Validate required headers
            $requiredHeaders = ['username', 'password', 'role'];
            $missingHeaders = [];
            foreach ($requiredHeaders as $req) {
                if (!in_array($req, $headers)) {
                    $missingHeaders[] = $req;
                }
            }

            if (!empty($missingHeaders)) {
                return response()->json([
                    'error' => 'Header yang wajib tidak ditemukan: ' . implode(', ', $missingHeaders)
                ], 400);
            }

            // Get header indices
            $headerIndices = [];
            foreach ($requiredHeaders as $req) {
                $headerIndices[$req] = array_search($req, $headers);
            }
            $headerIndices['no_hp'] = array_search('no_hp', $headers) !== false ? array_search('no_hp', $headers) : null;
            $headerIndices['email'] = array_search('email', $headers) !== false ? array_search('email', $headers) : null;
            $headerIndices['can_approve'] = array_search('can_approve', $headers) !== false ? array_search('can_approve', $headers) : null;

            // Process data rows (skip header)
            $dataRows = array_slice($rows, 1);
            $successCount = 0;
            $errorCount = 0;
            $errors = [];
            $createdUsers = [];

            foreach ($dataRows as $rowIndex => $row) {
                $actualRowNum = $rowIndex + 2; // +2 because array is 0-indexed and we skip header

                try {
                    // Extract values
                    $username = trim($row[$headerIndices['username']] ?? '');
                    $password = trim($row[$headerIndices['password']] ?? '');
                    $role = strtolower(trim($row[$headerIndices['role']] ?? ''));
                    $noHp = $headerIndices['no_hp'] !== null ? trim($row[$headerIndices['no_hp']] ?? '') : null;
                    $email = $headerIndices['email'] !== null ? trim($row[$headerIndices['email']] ?? '') : null;
                    $canApprove = $headerIndices['can_approve'] !== null ? trim($row[$headerIndices['can_approve']] ?? '0') : '0';

                    // Validation
                    if (empty($username) || strlen($username) < 3) {
                        $errors[] = "Baris $actualRowNum: Username wajib dan minimal 3 karakter";
                        $errorCount++;
                        continue;
                    }

                    if (empty($password) || strlen($password) < 6) {
                        $errors[] = "Baris $actualRowNum: Password wajib dan minimal 6 karakter";
                        $errorCount++;
                        continue;
                    }

                    if (!in_array($role, ['admin', 'user'])) {
                        $errors[] = "Baris $actualRowNum: Role harus 'admin' atau 'user'";
                        $errorCount++;
                        continue;
                    }

                    // Check if username already exists
                    if (User::where('username', $username)->exists()) {
                        $errors[] = "Baris $actualRowNum: Username '$username' sudah terdaftar";
                        $errorCount++;
                        continue;
                    }

                    // Convert can_approve to integer
                    $canApproveInt = 0;
                    if (in_array(strtolower($canApprove), ['1', 'true', 'yes', 'y', 'ya'])) {
                        $canApproveInt = 1;
                    }

                    // Create user
                    $user = User::create([
                        'username' => $username,
                        'password' => Hash::make($password),
                        'role' => $role,
                        'can_approve' => $canApproveInt,
                        'no_hp' => $noHp ?: null,
                        'email' => $email ?: null,
                    ]);

                    $createdUsers[] = $user;
                    $successCount++;

                } catch (\Exception $e) {
                    $errors[] = "Baris $actualRowNum: " . $e->getMessage();
                    $errorCount++;
                }
            }

            // Calculate KPI deltas
            $adminCount = 0;
            $adminActiveCount = 0;
            $userActiveCount = 0;
            foreach ($createdUsers as $user) {
                if ($user->role === 'admin') {
                    $adminCount++;
                    if ($user->can_approve) {
                        $adminActiveCount++;
                    }
                } else {
                    $userActiveCount++;
                }
            }

            $response = [
                'success' => true,
                'message' => "Import selesai. Berhasil: $successCount, Gagal: $errorCount",
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'users' => $createdUsers,
                'counts' => [
                    'totalDelta' => $successCount,
                    'adminTotalDelta' => $adminCount,
                    'adminActiveDelta' => $adminActiveCount,
                    'userActiveDelta' => $userActiveCount,
                ]
            ];

            if (!empty($errors)) {
                $response['errors'] = $errors;
            }

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengimport file Excel: ' . $e->getMessage()
            ], 500);
        }
    }
}
