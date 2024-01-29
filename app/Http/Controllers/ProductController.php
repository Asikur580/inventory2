<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = Product::with('brand', 'category')->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function show(Request $request): JsonResponse
    {
        $product_id = $request->input('id');

        $data = Product::where('id', '=', $product_id)->first();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function store(Request $request): JsonResponse
    {

        try {

            $validated = $request->validate([
                'img_url' => 'nullable|image|mimes:jpeg,png,jpg|max:5000',
                'name' => 'required|string',
                'measurement_unit' => 'required|string',
                'brand_id' => 'required|exists:brands,id',
                'category_id' => 'required|exists:categories,id',
                'sale_price' => 'required|string',
                'discount' => 'nullable|boolean',
                'discount_price' => 'nullable|string',
                'weight' => 'required|numeric|between:0,99999.999',
                'stock' => 'integer|min:0',
                'Variation' => 'nullable|string',
                'description' => 'nullable|string',
                'purchase_price' => 'nullable|string',
            ]);


            // Handle image upload if present

            if ($request->hasFile('img_url')) {

                $img = $request->file('img_url');

                $t = time();
                $file_name = $img->getClientOriginalName();
                $img_name = "{$t}-{$file_name}";
                $img_url = "uploads/{$img_name}";

                // Upload File
                $img->move(public_path('uploads'), $img_name);

                $request->merge(['img_url' => $img_url]);
            }

            // Create a new product using the create method

            Product::create($validated);

            return ResponseHelper::Out('success', '', 200);
        } catch (Exception $exception) {

            return ResponseHelper::Out('fail', $exception, 200);
        }
    }

    public function update(Request $request)
    {
        try {
            // Find the product by its ID
            $product = Product::where('id', $request->id)->first();

            // Validate the request data
            $validated = $request->validate([
                'img_url' => 'nullable|image|mimes:jpeg,png,jpg|max:5000',
                'name' => 'required|string',
                'measurement_unit' => 'required|string',
                'brand_id' => 'required|exists:brands,id',
                'category_id' => 'required|exists:categories,id',
                'sale_price' => 'required|string',
                'discount' => 'nullable|boolean',
                'discount_price' => 'nullable|string',
                'weight' => 'required|numeric|between:0,99999.999',
                'stock' => 'integer|min:0',
                'Variation' => 'nullable|string',
                'description' => 'nullable|string',
                'purchase_price' => 'nullable|string',
            ]);

            // Update the product data
            $data = $product->update($validated);

            // Handle image update if present
            if ($request->hasFile('img_url')) {

                // Upload New File
                $img = $request->file('img_url');
                $t = time();
                $file_name = $img->getClientOriginalName();
                $img_name = "{$t}-{$file_name}";
                $img_url = "uploads/{$img_name}";
                $img->move(public_path('uploads'), $img_name);

                // Delete Old File
                $filePath = $request->input('file_path');
                File::delete($filePath);

                $product->img_url = $img_url;
                $product->save();
            }

            return ResponseHelper::Out('success', $data, 200);
        } catch (Exception $exception) {
            return ResponseHelper::Out('fail', $exception, 200);
        }
    }


    public function destory(Request $request): JsonResponse
    {
        try {
            // Find the product by its ID
            $product = Product::where('id', $request->id)->first();

            // Delete the product's image if it exists
            if ($product->img_url) {
                Storage::disk('public')->delete($product->img_url);
            }

            // Delete the product
            $data = $product->delete();

            return ResponseHelper::Out('success', $data, 200);
        } catch (Exception $exception) {
            return ResponseHelper::Out('fail', $exception, 200);
        }
    }

    public function CreateCartList(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'product_name' => 'nullable|string',
                'qty'        => 'required|integer|min:1',
                'price'      => 'required|numeric',
            ]);

            $user_id = $request->header('id');            
            $product_id = $request->input('product_id') ?? '';  
            $product_name =  $request->input('product_name');         
            $qty = $request->input('qty');
            $UnitPrice = $request->input('price');

            if ($product_id == null) {
                $data = ProductCart::Create([
                    'product_name' => $product_name,
                    'user_id' => $user_id,
                    'qty' => $qty,
                    'price' => $UnitPrice
                ]);

                return ResponseHelper::Out('success', $data, 200);
            } else {

                $productDetails = Product::where('id', '=', $product_id)->first();

                if ($productDetails->discount == 1) {
                    $UnitPrice = $productDetails->discount_price;
                } else {
                    $UnitPrice = $productDetails->sale_price;
                }
                $totalPrice = $qty * $UnitPrice;

                $data = ProductCart::updateOrCreate(
                    ['user_id' => $user_id, 'product_id' => $product_id],
                    [
                        'user_id' => $user_id,
                        'product_id' => $product_id,
                        'qty' => $qty,
                        'price' => $totalPrice
                    ]
                );

                return ResponseHelper::Out('success', $data, 200);
            }
        } catch (Exception $exception) {
            return ResponseHelper::Out('fail', $exception, 200);
        }
    }

    public function CartList(Request $request): JsonResponse
    {

        $user_id = $request->header('id');
        $data = ProductCart::where('user_id', $user_id)->with('product')->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function DeleteCartList(Request $request): JsonResponse
    {
        $user_id = $request->header('id');
        $data = ProductCart::where('user_id', '=', $user_id)->where('product_id', '=', $request->product_id)->delete();
        return ResponseHelper::Out('success', $data, 200);
    }
}
