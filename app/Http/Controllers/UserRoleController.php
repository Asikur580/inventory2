<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;
use Exception;

class UserRoleController extends Controller
{
    public function index(Request $request)
    {
        return UserRole::all();
    }

    public function show(Request $request)
    {
        $userRole_id = $request->input('id');

        return UserRole::where('id', '=', $userRole_id)->first();
    }

    public function store(Request $request)
    {

        try {
            $request->validate([
                'user_id' => 'required|min:1',
                'role_id' => 'required|min:1'
            ]);

            UserRole::create([
                'user_id' => $request->input('user_id'),
                'role_id' => $request->input('role_id')
            ]);

            return response()->json(['status' => 'success', 'message' => 'UserRole Create Successfully']);
        } catch (Exception $exception) {

            return response()->json(['status' => 'failed', 'message' => $exception->getMessage()]);
        }
    }

    public function update(Request $request){
        try {
            $request->validate([
                'user_id' => 'required|min:1',
                'role_id' => 'required|min:1',               
                'id'=>'required|min:1'
            ]);

            $userRole_id=$request->input('id');
            
            UserRole::where('id',$userRole_id)->update([
                'user_id' => $request->input('user_id'),
                'role_id' => $request->input('role_id')
            ]);
            return response()->json(['status' => 'success', 'message' => "Update Successful"]);
        }catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }

    public function destory(Request $request){
        try {
            $request->validate([
                'id' => 'required|string|min:1'
            ]);

            $userRole_id=$request->input('id');
            
            UserRole::where('id',$userRole_id)->delete();

            return response()->json(['status' => 'success', 'message' => "Delete Successful"]);
            
        }catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }
}
