<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HoaDon extends Model
{
    use HasFactory;
    protected $table = 'hoa_don';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'user_id',
        'sdt',
        'ten_khach_hang',
        'ngay_ban',
        'tong_tien',
        'chuyen_khoan',
        'note',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function chiTietHD() : HasMany
    {
        return $this->hasMany(ChiTietHoaDon::class, 'ma_hoa_don', 'id');
    }
}
