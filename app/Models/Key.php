<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    use HasFactory;

    protected $table = 'tokens';
    protected $fillable = [
        'token',
        'email',
        'api',
        'created_at',
        'updated_at'
    ];
}
