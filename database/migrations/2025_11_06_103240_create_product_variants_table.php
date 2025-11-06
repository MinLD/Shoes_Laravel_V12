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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
           // Khóa ngoại nối với sản phẩm gốc
            $table->unsignedBigInteger('product_id');

            // Các thuộc tính (có thể rỗng nếu sản phẩm không có)
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            // Bạn có thể thêm 'material' (chất liệu) v.v. nếu muốn

            // Giá và kho của B-I-Ế-N TH-Ể này
            $table->decimal('price', 10, 2); // Giá (ví dụ: 1000.00)
            $table->integer('stock_quantity')->default(0); // Số lượng tồn kho

            // Ảnh đại diện C-Ủ-A RI-Ê-NG biến thể này
            $table->string('image_url')->nullable(); 
            
            $table->timestamps();

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
