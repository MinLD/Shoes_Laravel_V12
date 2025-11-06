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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            
            // Có thể null, phòng trường hợp sản phẩm bị xóa
            $table->unsignedBigInteger('product_variant_id')->nullable(); 
            
            $table->integer('quantity');

            // --- Đây là Snapshot (Bản ghi lịch sử) ---
            // Sao chép tên/thông tin biến thể tại thời điểm mua
            $table->string('product_name'); 
            // Sao chép giá tại thời điểm mua
            $table->decimal('price', 10, 2); 
            // --- Hết Snapshot ---
            
            $table->timestamps();

            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');
            
            // Nối (1 chiều) tới biến thể, nhưng set null nếu bị xóa
            $table->foreign('product_variant_id')
                  ->references('id')
                  ->on('product_variants')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
