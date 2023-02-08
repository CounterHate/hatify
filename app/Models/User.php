<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
// use Spatie\Permission\Traits\HasRoles;
// use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;


class User extends Authenticatable
{
    // use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tweets()
    {
        return $this->hasMany(Tweet::class);
    }

    public function tweetCount()
    {
        return $this->tweets()->count();
    }

    public function verifiedCount()
    {
        return $this->tweets()->where('verified', 1)->count();
    }

    public function notSureCount()
    {
        return $this->tweets()->where('is_hate_speech', 0)->count();
    }

    public function anotatedCount()
    {
        return $this->tweets()->where('is_hate_speech', 1)->count() - $this->verifiedCount();
    }

    // public function getAllPermissionsAttribute() {
    //     $permissions = [];
    //       foreach (Permission::all() as $permission) {
    //         if (Auth::user()->can($permission->name)) {
    //           $permissions[] = $permission->name;
    //         }
    //       }
    //       return $permissions;
    //   }
}
