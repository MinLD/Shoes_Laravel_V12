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
            // Khóa ngoại nối với bảng categories
            $table->unsignedBigInteger('category_id'); 
            
            $table->string('name'); // Tên sản phẩm gốc, ví dụ: "Áo Thun Cổ Tròn"
            $table->string('slug')->unique();
            $table->text('description')->nullable(); // Mô tả chung
            
            $table->timestamps();

            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade'); // Xóa sản phẩm nếu danh mục bị xóa
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
