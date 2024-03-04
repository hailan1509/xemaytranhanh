<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SanPham extends Model
{
    use HasFactory;
    protected $table = 'san_pham';
    protected $fillable = [
        'id',
        'user_id',
        'name',
        'short_name',
        'hang_xe',
        'nhom_hang',
        'gia_ban',
        'gia_nhap',
        'so_luong_con_lai',
        'so_luong_nhap',
        'phuong_thuc_nhap',
        'ngay_nhap',
        'note',
        'nha_cung_cap',
        'img'
    ];

    public function hangXe(): HasOne
    {
        return $this->hasOne(HangXe::class, 'id', 'hang_xe');
    }
    public function nhomHang(): HasOne
    {
        return $this->hasOne(NhomHang::class, 'id', 'nhom_hang');
    }
    public function nhaCungCap(): HasOne
    {
        return $this->hasOne(NhaCungCap::class, 'id', 'nha_cung_cap');
    }
    public function imgPath() {
        return public_path(). '/images/posts/' .$this->img;
    }
}
