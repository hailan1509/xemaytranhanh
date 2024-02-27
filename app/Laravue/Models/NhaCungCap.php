<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhaCungCap extends Model
{
    use HasFactory;
    protected $table = 'khach_hang';
    protected $fillable = [
        'id',
        'user_id',
        'name',
        'address',
        'phone',
        'note',
    ];
}
