<?php

namespace App\Mail;

use App\Models\Order; // <-- Đảm bảo có dòng này
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Biến public $order sẽ TỰ ĐỘNG được truyền vào view.
     * Đây là phần quan trọng nhất.
     */
    public $order;

    /**
     * Tạo một instance message mới.
     */
    public function __construct(Order $order)
    {
        // Gán $order từ Controller vào biến public
        $this->order = $order;
    }

    /**
     * Lấy envelope (vỏ) của message.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // Tiêu đề email
            subject: 'Xác nhận Đơn hàng #' . $this->order->id,
        );
    }

    /**
     * Lấy nội dung (content) của message.
     */
    public function content(): Content
    {
        return new Content(
            // Trỏ đến view mà chúng ta đã tạo
            view: 'emails.orders.placed',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}