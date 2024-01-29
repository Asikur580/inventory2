<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;

// page route 

Route::view('/','pages.auth.login-page');
Route::view('/sendOtp','pages.auth.send-otp-page');


Route::view('/dashboard','pages.dashboard.dashboard-page');
Route::view('/userPage','pages.dashboard.user-page');
Route::view('/rolePage','pages.dashboard.role-page');
Route::view('/customerPage','pages.dashboard.customer-page');
Route::view('/supplierPage','pages.dashboard.supplier-page');
Route::view('/productPage','pages.dashboard.product-page');
Route::view('/categoryPage','pages.dashboard.category-page');
Route::view('/brandPage','pages.dashboard.brand-page');


// user route

Route::post('/login',[UserController::class,'login'])->name('user.login');
Route::get('/user',[UserController::class,'index'])->name('user.index');
Route::post('/userById',[UserController::class,'show'])->name('user.show');
Route::post('/user',[UserController::class,'store'])->name('user.store');
Route::post('/userUpdate',[UserController::class,'update'])->name('user.update');


// role route

Route::get('/role',[RoleController::class,'index'])->name('role.index');
Route::post('/roleById',[RoleController::class,'show'])->name('role.show');
Route::post('/role',[RoleController::class,'store'])->name('role.store');
Route::post('/roleUpdate',[RoleController::class,'update'])->name('role.update');
Route::post('/roleDelete',[RoleController::class,'destory'])->name('role.destory');


// userRole route 

Route::get('/userRole',[UserRoleController::class,'index'])->name('userRole.index');
Route::post('/userRoleById',[UserRoleController::class,'show'])->name('userRole.show');
Route::post('/userRole',[UserRoleController::class,'store'])->name('userRole.store');
Route::post('/userRoleUpdate',[UserRoleController::class,'update'])->name('userRole.update');
Route::post('/userRoleDelete',[UserRoleController::class,'destory'])->name('userRole.destory');


// category route

Route::get('/category',[CategoryController::class,'index'])->name('category.index');
Route::post('/categoryById',[CategoryController::class,'show'])->name('category.show');
Route::post('/category',[CategoryController::class,'store'])->name('category.store');
Route::post('/categoryUpdate',[CategoryController::class,'update'])->name('category.update');
Route::post('/categoryDelete',[CategoryController::class,'destory'])->name('category.destory');

// brand route

Route::get('/brand',[BrandController::class,'index'])->name('brand.index');
Route::post('/brandById',[BrandController::class,'show'])->name('brand.show');
Route::post('/brand',[BrandController::class,'store'])->name('brand.store');
Route::post('/brandUpdate',[BrandController::class,'update'])->name('brand.update');
Route::post('/brandDelete',[BrandController::class,'destory'])->name('brand.destory');

// product route

Route::get('/product',[ProductController::class,'index'])->name('product.index');
Route::post('/productById',[ProductController::class,'show'])->name('product.show');
Route::post('/product',[ProductController::class,'store'])->name('product.store');
Route::post('/productUpdate',[ProductController::class,'update'])->name('product.update');
Route::post('/productDelete',[ProductController::class,'destory'])->name('product.destory');

// supplier route

Route::get('/supplier',[SupplierController::class,'index'])->name('supplier.index');
Route::post('/supplierById',[SupplierController::class,'show'])->name('supplier.show');
Route::post('/supplier',[SupplierController::class,'store'])->name('supplier.store');
Route::post('/supplierUpdate',[SupplierController::class,'update'])->name('supplier.update');
Route::post('/supplierDelete',[SupplierController::class,'destory'])->name('supplier.destory');

// customer route

Route::get('/customer',[CustomerController::class,'index'])->name('customer.index');
Route::post('/customerById',[CustomerController::class,'show'])->name('customer.show');
Route::post('/customer',[CustomerController::class,'store'])->name('customer.store');
Route::post('/customerUpdate',[CustomerController::class,'update'])->name('customer.update');
Route::post('/customerDelete',[CustomerController::class,'destory'])->name('customer.destory');

// Product Cart
Route::post('/CreateCartList', [ProductController::class, 'CreateCartList']);
Route::get('/CartList', [ProductController::class, 'CartList']);
Route::get('/DeleteCartList/{product_id}', [ProductController::class, 'DeleteCartList']);

// invoice route

Route::get('/invoice',[InvoiceController::class,'index'])->name('invoice.index');
Route::post('/invoiceById',[InvoiceController::class,'show'])->name('invoice.show');
Route::post("/invoice",[InvoiceController::class,'store'])->name('invoice.store');
Route::post("/invoiceUpdate",[InvoiceController::class,'update'])->name('invoice.update');
