<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DichVu extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'dich_vu';
    protected $fillable = [
        'id',
        'user_id',
        'ten_dich_vu',
        'gia_ban',
        'note'
    ];
}
