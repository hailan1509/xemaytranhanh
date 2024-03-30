<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChiTietDichVu extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'chi_tiet_dich_vu';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'user_id',
        'ma_hoa_don',
        'ma_dich_vu',
        'gia_ban',
        'so_luong',
        'note',
        'deleted_at',
        'created_at',
        'updated_at',
        'ma_khuyen_mai',
    ];

    public function dichVu() : HasOne
    {
        return $this->hasOne(DichVu::class, 'id', 'ma_dich_vu');
    }

    public function hoaDon() : HasOne
    {
        return $this->hasOne(HoaDon::class, 'id', 'ma_hoa_don');
    }
}
