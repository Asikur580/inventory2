<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Role;
use Exception;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        return Role::all();
    }

    public function show(Request $request)
    {
        $role_id = $request->input('id');
        return Role::where('id', '=', $role_id)->first();
    }

    public function store(Request $request)
    {

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
            ]);

            Role::create([
                'name' => $request->input('name'),
                'slug' => $request->input('slug')
            ]);

            return response()->json(['status' => 'success', 'message' => 'Role Create Successfully']);
        } catch (Exception $exception) {

            return response()->json(['status' => 'failed', 'message' => $exception->getMessage()]);
        }
    }

    public function update(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:50',
            'slug' => 'required|string|max:50'
        ]);

        $role_id = $request->input('id');

        return Role::where('id', $role_id)->update([
            'name' => $request->input('name'),
            'slug' => $request->input('slug')
        ]);
    }

    public function destory(Request $request)
    {

        $role_id = $request->input('id');

        return Role::where('id', $role_id)->delete();
    }
}
