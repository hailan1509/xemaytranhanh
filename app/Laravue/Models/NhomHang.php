<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhomHang extends Model
{
    use HasFactory;
    protected $table = 'nhom_hang';
    protected $fillable = [
        'id',
        'user_id',
        'name',
        'note',
    ];
}
