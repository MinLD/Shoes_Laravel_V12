<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;
    // Bảo vệ các trường (nên dùng)
    protected $fillable = [
        'user_id', 
        'phone_number', 
        'date_of_birth', 
        'address', 
        'avatar'
    ];
    /**
     * Lấy user sở hữu profile này (Quan hệ 1-1 đảo ngược).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
