<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\User;
use App\Helper\JWTHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\UserRole;

class userController extends Controller
{
    public function login(Request $request)
    {

        try {
            $request->validate([
                'email' => 'required|string|email|max:50',
                'password' => 'required|string|min:3'
            ]);


            $user = User::where('email', '=', $request->input('email'))->first();

            if (!$user || !Hash::check($request->input('password'), $user->password)) {

                return response()->json(['status' => 'failed', 'message' => 'Password not match']);
            }

            $userId = $user->id;

            if ($userId > 0) {

                $role = $user->roles->pluck('slug')->all();

                $token = JWTHelper::CreateToken($request->input('email'), $userId, $role);
                return response()->json(['status' => 'success', 'message' => 'User Login Successfully'])->cookie('token', $token, time() + 60 * 60);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'No user found']);
            }
        } catch (Exception $exception) {
            return response()->json(['status' => 'failed', 'message' => $exception->getMessage()]);
        }
    }

    public function index(Request $request)
    {
        $user = User::with('roles')->get();

        return $user;
    }

    public function show(Request $request)
    {
        $user_id = $request->input('id');

        return User::with('roles')->where('id', '=', $user_id)->first();
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'firstName' => 'required|string|max:50',
                'lastName' => 'required|string|max:50',
                'email' => 'required|string|email|max:50|unique:users,email',
                'mobile' => 'required|string|max:50',
                'password' => 'required|string|min:6',
                'role' => 'required'
            ]);

            $user = User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => Hash::make($request->input('password'))
            ]);

            $user_id = $user->id;

            UserRole::create([
                'user_id' => $user_id,
                'role_id' => $request->input('role')
            ]);


            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'User Create Successfully']);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json(['status' => 'failed', 'message' => $exception->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'firstName' => 'required|string|max:50',
                'lastName' => 'required|string|max:50',
                'mobile' => 'required|string|max:50',
                'roles' => 'required',
                'id' => 'required|min:1',
            ]);

            $user_id = $request->input('id');

            $user = User::where('id', $user_id)->update([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'mobile' => $request->input('mobile')
            ]);

            UserRole::where('user_id', '=', $user_id)->delete();

            $roles = $request->input('roles');

            foreach ($roles as $role) {
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $role['role_id']
                ]);
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'User update Successfully']);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json(['status' => 'failed', 'message' => $exception->getMessage()]);
        }
    }
}
