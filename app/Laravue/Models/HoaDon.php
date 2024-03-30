<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'ngay_sinh',
        'ngay_ban',
        'tong_tien',
        'chuyen_khoan',
        'note',
        'dia_chi',
        'deleted_at',
        'created_at',
        'updated_at',
        'type',
        'nha_xuat_ban',
        'cccd',
    ];

    public function chiTiet() : HasMany
    {
        return $this->hasMany(ChiTietHoaDon::class, 'ma_hoa_don', 'id');
    }
    public function dichVus() : HasMany
    {
        return $this->hasMany(ChiTietDichVu::class, 'ma_hoa_don', 'id');
    }
    public function nxb(): HasOne
    {
        return $this->hasOne(NhaXuatBan::class, 'id', 'nha_xuat_ban');
    }
}
