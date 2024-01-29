<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = Customer::all();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function show(Request $request): JsonResponse
    {
        $customer_id = $request->input('id');
        $data = Customer::where('id', '=', $customer_id)->first();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function store(Request $request): JsonResponse
    {

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:50|unique:customers,email',
                'mobile' => 'required|string|max:50'
            ]);

            Customer::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
            ]);

            return ResponseHelper::Out('success','', 200);
        } catch (Exception $exception) {

            return ResponseHelper::Out('fail', $exception, 200);
        }
    }

    public function update(Request $request): JsonResponse
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:50|',
            'mobile' => 'required|string|max:50'
        ]);

        $customer_id = $request->input('id');

        $data = Customer::where('id', $customer_id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
        ]);
        return ResponseHelper::Out('success', $data, 200);
    }

    public function destory(Request $request): JsonResponse
    {
        $customer_id = $request->input('id');
        $data = Customer::where('id', $customer_id)->delete();
        return ResponseHelper::Out('success', $data, 200);
    }
}
