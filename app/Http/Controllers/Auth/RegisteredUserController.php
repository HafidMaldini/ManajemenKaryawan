<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        if(Auth::user()->team->name === 'HRD'){
            $users = User::with(['role','team'])->get();
            $roles = Role::all();
            $teams = Team::all();
            return view('index.user', compact('users', 'roles', 'teams'));
        }else{
            abort(403, 'Anda tidak memiliki akses!');
        }
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:10'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:50', 'unique:'.User::class, 'regex:/^[^@]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'integer'],
            'team_id' => ['required', 'integer', 'exists:teams,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'team_id' => $request->team_id,
            'role_id' => $request->role_id,
            'sisa_cuti' => 3,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat.',
            'data' => $user,
        ], 201); 
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:10'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:50', 'unique:'.User::class. ',email,' .$id , 'regex:/^[^@]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$/'],
            'role_id' => ['required', 'integer'],
            'team_id' => ['required', 'integer', 'exists:teams,id'],
        ]);
        
        
        $user = User::findOrFail($id);
        
        if( isset($request->password)){
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'team_id' => $request->team_id,
                'role_id' => $request->role_id,
            ]);
        }
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $user->password,
            'team_id' => $request->team_id,
            'role_id' => $request->role_id,
        ]);
            
        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diperbarui.',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 404); // 404 adalah status code untuk "Not Found"
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.'
        ]);
    }
}
