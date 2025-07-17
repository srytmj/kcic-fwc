<?php

namespace App\Http\Controllers;

use App\Models\UserManagement;
use App\Http\Requests\StoreUserManagementRequest;
use App\Http\Requests\UpdateUserManagementRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Monolog\Level;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = UserManagement::all();

        return view('user.index', compact('data'));
        // return response()->json($userManagements);
        // return response()->json(['message' => 'User Management Index']);
        // return response()->json(['data' => $userManagements]);
        // return response()->json(['status' => 'success', 'data' => $userManagements]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('user.register');
        // return response()->json(['message' => 'User Management Create']);
        // return response()->json(['status' => 'success', 'message' => 'User Management Create']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserManagementRequest $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'level' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = UserManagement::create([
            'name' => $request->name,
            'email' => $request->email,
            'level' => $request->level,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([$user]);
        // return redirect()->back()->with('success', 'Registrasi user berhasil!');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserManagement $userManagement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = UserManagement::findOrFail($id);

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserManagement $userManagement)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userManagement->id,
            'level' => 'required|in:1,2',
            'password' => 'nullable|confirmed|min:8',
        ]);

        $userManagement->name = $validated['name'];
        $userManagement->email = $validated['email'];
        $userManagement->level = $validated['level'];

        if (!empty($validated['password'])) {
            $userManagement->password = Hash::make($validated['password']);
        }

        $userManagement->save();

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Hapus user
        $user->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
