<?php

namespace App;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $table = 'operator';

    protected $fillable = [
       'nama',
       'username',
       'password',
       'bagian',
       'status_aktif'
    ];

    protected $hidden = ['password'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
