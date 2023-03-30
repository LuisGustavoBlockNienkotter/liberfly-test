<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// /**
//  * @OA\Info(
//  *      version="1.0.0",
//  *      x={
//  *          "logo": {
//  *              "url": "https://via.placeholder.com/190x90.png?text=L5-Swagger"
//  *          }
//  *      },
//  *      title="User",
//  *      description="User model, used to login in the application",
//  *      @OA\Contact(
//  *          email="luis.block.nienk@gmail.com"
//  *      )
//  * )
//  */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}