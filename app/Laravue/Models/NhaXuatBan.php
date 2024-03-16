<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhaXuatBan extends Model
{
    use HasFactory;
    protected $table = 'nha_xuat_ban';
    protected $fillable = [
        'id',
        'user_id',
        'name',
        'address',
        'phone',
        'note',
    ];
}
