<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ChiTietHoaDon extends Model
{
    use HasFactory;
    protected $table = 'chi_tiet_hoa_don';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'user_id',
        'ma_hoa_don',
        'ma_san_pham',
        'gia_ban',
        'so_luong',
        'note',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function sanPham() : HasOne
    {
        return $this->hasOne(SanPham::class, 'id', 'ma_san_pham');
    }
}
