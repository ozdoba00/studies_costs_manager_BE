<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    private const PUBLIC_ID_LENGTH = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'public_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Generating public random id for users
     * 
     * @return string
     */
    public static function generateUniquePublicID()
    {
        $random_id = '';

        do
        {
            $random_id = bin2hex(random_bytes(self::PUBLIC_ID_LENGTH));
            $user = User::where('public_id', $random_id)->get();
        }
        while(!$user->isEmpty());

        return $random_id;
    }
}
