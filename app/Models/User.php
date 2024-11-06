<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'phone', 'email', 'city', 'birthday', 'image'];

    protected $hidden = [
        'password'
    ];

    public function workers()
    {
        return $this->hasMany(Worker::class);
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }
}
