<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImportProduct extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'deleted_at',
        'updated_at',
        'created_at',
    ];

    protected $fillable = [
        'file',
        'user_id'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
