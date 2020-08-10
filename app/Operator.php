<?php

namespace App;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;

class Operator extends Authenticable
{
    use Notifiable;

    protected $guard = 'operator';

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

    public function notifikasiOperator(){
        return $this->hasMany('App\NotifikasiOperator','id_operator');
    }
}
