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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->integer('quantity')->default(1); // Số lượng
            
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('cart_id')
                  ->references('id')
                  ->on('carts')
                  ->onDelete('cascade');
            
            $table->foreign('product_variant_id')
                  ->references('id')
                  ->on('product_variants')
                  ->onDelete('cascade'); // Xóa món hàng nếu biến thể bị xóa

            // Đảm bảo 1 biến thể chỉ xuất hiện 1 lần trong 1 giỏ hàng
            $table->unique(['cart_id', 'product_variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
