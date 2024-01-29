<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Http\JsonResponse;


class BrandController extends Controller
{
    public function index(Request $request):JsonResponse
    {
        $data = Brand::all();

        return ResponseHelper::Out('success',$data,200);
    }

    public function show(Request $request):JsonResponse
    {
        $brand_id = $request->input('id');
        $data = Brand::where('id', '=', $brand_id)->first();
        return ResponseHelper::Out('success',$data,200);
    }

    public function store(Request $request):JsonResponse
    {

        try {
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            Brand::create([
                'name' => $request->input('name')
            ]);

            return ResponseHelper::Out('success','',200);
            
        } catch (Exception $exception) {

            return ResponseHelper::Out('fail',$exception,200);
        }
    }

    public function update(Request $request):JsonResponse
    {

        $request->validate([
            'name' => 'required|string|max:50'
        ]);

        $brand_id = $request->input('id');

        $data =  Brand::where('id', $brand_id)->update([
            'name' => $request->input('name')
        ]);
        return ResponseHelper::Out('success',$data,200);
    }

    public function destory(Request $request):JsonResponse
    {

        $brand_id = $request->input('id');

        $data =  Brand::where('id', $brand_id)->delete();
        return ResponseHelper::Out('success',$data,200);
    }
}
