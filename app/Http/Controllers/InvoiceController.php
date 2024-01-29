<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Models\InvoiceProduct;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = Invoice::with('customer', 'supplier', 'invoiceProducts')->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function show(Request $request): JsonResponse
    {
        $user_id = $request->header('id');
        $customerDetails = Customer::where('id', $request->input('cus_id'))->first();
        $supplierDetails = Supplier::where('id', $request->input('supp_id'))->first();
        $invoiceTotal = Invoice::where('user_id', '=', $user_id)->where('id', $request->input('inv_id'))->first();
        $invoiceProduct = InvoiceProduct::where('invoice_id', $request->input('inv_id'))
            ->where('user_id', $user_id)->with('product')
            ->get();
        $data = array(
            'customer' => $customerDetails,
            'supplier' => $supplierDetails,
            'invoice' => $invoiceTotal,
            'product' => $invoiceProduct,
        );
        return ResponseHelper::Out('success', $data, 200);
    }

    public function store(Request $request): JsonResponse
    {

        DB::beginTransaction();

        try {

            $validated = $request->validate([
                'discount' => 'numeric',
                'less' => 'numeric',
                'vat' => 'nullable|numeric',
                'in_cash' => 'nullable|numeric',
                'customer_id' => 'nullable|integer|exists:customers,id',
                'supplier_id' => 'nullable|integer|exists:suppliers,id',
            ]);

            $user_id = $request->header('id');


            // Payable Calculation
            $total = 0;
            $cartList = ProductCart::where('user_id', '=', $user_id)->get();

            foreach ($cartList as $cartItem) {
                $total = $total + $cartItem->price;
            }

            $discount = $request->input('discount');
            $less = $request->input('less');
            $vat = ($total * 3) / 100;
            $payable = ($total + $vat) - ($discount + $less);
            $in_cash = $request->input('in_cash');
            $due = $payable - $in_cash;

            $customer_id = $request->input('customer_id');
            $supplier_id = $request->input('supplier_id');

            if ($cartList->isNotEmpty()) {
                if ($customer_id) {
                    $invoice = Invoice::create([
                        'total' => $total,
                        'discount' => $discount,
                        'less' => $less,
                        'vat' => $vat,
                        'payable' => $payable,
                        'in_cash' => $in_cash,
                        'due' => $due,
                        'user_id' => $user_id,
                        'customer_id' => $customer_id,
                    ]);
                } else if ($supplier_id) {
                    $invoice = Invoice::create([
                        'total' => $total,
                        'discount' => $discount,
                        'less' => $less,
                        'vat' => $vat,
                        'payable' => $payable,
                        'in_cash' => $in_cash,
                        'due' => $due,
                        'user_id' => $user_id,
                        'supplier_id' => $supplier_id,
                    ]);
                } else {
                    $invoice = Invoice::create([
                        'total' => $total,
                        'discount' => $discount,
                        'less' => $less,
                        'vat' => $vat,
                        'payable' => $payable,
                        'in_cash' => $in_cash,
                        'user_id' => $user_id
                    ]);
                }

                $invoiceID = $invoice->id;

                foreach ($cartList as $EachProduct) {
                    if ($EachProduct['product_id'] == null) {
                        InvoiceProduct::create([
                            'invoice_id' => $invoiceID,
                            'user_id' => $user_id,
                            'qty' =>  $EachProduct['qty'],
                            'product_name' =>  $EachProduct['product_name'],
                            'sale_price' =>  $EachProduct['price'],
                        ]);
                    } else {
                        InvoiceProduct::create([
                            'invoice_id' => $invoiceID,
                            'product_id' => $EachProduct['product_id'],
                            'user_id' => $user_id,
                            'qty' =>  $EachProduct['qty'],
                            'sale_price' =>  $EachProduct['price'],
                        ]);
                    }
                }

                $data = ProductCart::where('user_id', '=', $user_id)->delete();

                DB::commit();

                return ResponseHelper::Out('success', $data, 200);
            } else {
                $data = 'CartList data not found';

                return ResponseHelper::Out('fail', $data, 200);
            }
        } catch (Exception $exception) {
            DB::rollBack();
            return ResponseHelper::Out('fail', $exception, 200);
        }
    }

    public function update(Request $request): JsonResponse
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'discount' => 'numeric',
                'less' => 'numeric',
                'vat' => 'nullable|numeric',
                'in_cash' => 'nullable|numeric',
                'customer_id' => 'nullable|integer|exists:customers,id',
                'supplier_id' => 'nullable|integer|exists:suppliers,id',
            ]);

            $user_id = $request->header('id');
            $id = $request->input('id');

            $invoice = Invoice::findOrFail($id);

            $total = 0;
            $productList = $request->input('products');

            foreach ($productList as $productItem) {
                $total = $total + $productItem['sale_price'];
            }

            $discount = $request->input('discount');
            $less = $request->input('less');
            $vat = ($total * 3) / 100;
            $payable = ($total + $vat) - ($discount + $less);
            $in_cash = $request->input('in_cash');
            $due = $payable - $in_cash;

            $customer_id = $request->input('customer_id') ?? null;
            $supplier_id = $request->input('supplier_id') ?? null;

            $invoice->update([
                'total' => $total,
                'discount' => $discount,
                'less' => $less,
                'vat' => $vat,
                'payable' => $payable,
                'in_cash' => $in_cash,
                'due' => $due,
                'user_id' => $user_id,
                'customer_id' => $customer_id,
                'supplier_id' => $supplier_id
            ]);

            foreach ($productList as $productData) {

                $productId = $productData['id'] ?? null;

                if ($productId) {
                    // Update existing product
                    $invoiceProduct = InvoiceProduct::findOrFail($productId);

                    $invoiceProduct->update([
                        'qty' => $productData['qty'],
                        'sale_price' => $productData['sale_price'],
                        'return' => $productData['return'] ?? null,
                        'invoice_id' => $invoice->id,
                        'user_id' => $user_id,
                        'product_name' =>  $productData['product_name'] ?? null,
                        'product_id' => $productData['product_id'] ?? null
                    ]);
                } else {
                    // Create new product
                    $invoice->invoiceProducts()->create([
                        'qty' => $productData['qty'],
                        'sale_price' => $productData['sale_price'],
                        'return' => $productData['return'] ?? null,
                        'invoice_id' => $invoice->id,
                        'user_id' => $user_id,
                        'product_name' =>  $productData['product_name'] ?? null,
                        'product_id' => $productData['product_id'] ?? null
                    ]);
                }
            }


            $removedProductIds = collect($request->input('products'))->pluck('id');
            $invoice->invoiceProducts()->whereNotIn('id', $removedProductIds)->delete();

            DB::commit();

            return ResponseHelper::Out('success', $invoice, 200);
        } catch (Exception $exception) {

            DB::rollBack();
            return ResponseHelper::Out('fail', $exception->getMessage(), 200);
        }
    }
}
