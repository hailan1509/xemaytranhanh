<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HangXe extends Model
{
    use HasFactory;
    protected $table = 'hang_xe';
    protected $fillable = [
        'id',
        'name',
    ];
}
