<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data =  Category::all();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function show(Request $request): JsonResponse
    {
        $category_id = $request->input('id');
        $data = Category::where('id', '=', $category_id)->first();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function store(Request $request): JsonResponse
    {

        try {
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            Category::create([
                'name' => $request->input('name')
            ]);

            return ResponseHelper::Out('success', '', 200);
        } catch (Exception $exception) {

            return ResponseHelper::Out('fail', $exception, 200);
        }
    }

    public function update(Request $request): JsonResponse
    {

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category_id = $request->input('id');

        $data = Category::where('id', $category_id)->update([
            'name' => $request->input('name')
        ]);
        return ResponseHelper::Out('success', $data, 200);
    }

    public function destory(Request $request): JsonResponse
    {

        $category_id = $request->input('id');
        $data = Category::where('id', $category_id)->delete();
        return ResponseHelper::Out('success', $data, 200);
    }
}
