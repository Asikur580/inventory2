<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('img_url')->nullable();
            $table->string('name');
            $table->string('measurement_unit');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('category_id');
            $table->string('sale_price');
            $table->boolean('discount')->nullable();
            $table->string('discount_price')->nullable();
            $table->double('weight', 8, 3);
            $table->integer('stock')->default(0);
            $table->string('Variation')->nullable();
            $table->text('description')->nullable();            
            $table->string('purchase_price')->nullable();

            $table->foreign('brand_id')->references('id')->on('brands')
                ->cascadeOnUpdate()->restrictOnDelete();

            $table->foreign('category_id')->references('id')->on('categories')
                ->cascadeOnUpdate()->restrictOnDelete();           


            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
