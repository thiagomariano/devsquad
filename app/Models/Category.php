<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'deleted_at',
        'updated_at',
        'created_at'
    ];

    protected $fillable = [
        'name',
    ];
}
