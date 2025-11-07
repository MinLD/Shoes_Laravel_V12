<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Role;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable

{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Phương thức boot của model.
     * Tự động tạo profile khi user được tạo.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Tự động tạo một profile rỗng cho user mới
            $user->profile()->create([
                // bạn có thể thêm các giá trị mặc định ở đây nếu muốn
                // ví dụ: 'avatar' => 'default_avatar.png'
                'avatar' => 'default_avatar.png',
                'phone_number' => '0123456789',
                'date_of_birth' => '2000-01-01',
                'address' => 'Hồ Chí Minh',
            ]);
        });
    }
    //1-1
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
    //n-n
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
    /**
     * Kiểm tra xem user có một vai trò cụ thể hay không.
     */
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Mỗi User có (1) giỏ hàng.
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Mỗi User có (N) đơn hàng.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }


}
