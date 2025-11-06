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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
           // Khóa ngoại 1-N: 1 user có N đơn hàng
            $table->unsignedBigInteger('user_id'); 
            
            $table->decimal('total_amount', 10, 2); // Tổng tiền
            
            // Trạng thái đơn hàng
            $table->string('status')->default('pending'); // pending, processing, shipped, cancelled

            // --- Đây là Snapshot (Bản ghi lịch sử) ---
            $table->text('shipping_address'); // Địa chỉ lúc đặt hàng
            $table->string('phone_number');   // SĐT lúc đặt hàng
            // --- Hết Snapshot ---
            
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); // Xóa đơn hàng nếu user bị xóa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
