<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.User.'.$this->id;
    }

    protected $searchable = [
        'columns'   => [
            'users.name'        => 10,
            'users.username'    => 10,
            'users.email'       => 10,
            'users.mobile'      => 10,
            'users.bio'         => 10,
        ],
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function status()
    {
        return $this->status == '1' ? 'Active' : 'Inactive';
    }

    public function userImage()
    {
        return $this->user_image != '' ? asset('assets/users/' .$this->user_image) : asset('assets/users/default.png');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isAdmin()
    {
        return $this->role_id == Role::IS_ADMIN;
    }

    public function isEditor()
    {
        return $this->role_id == Role::IS_EDITOR;
    }

    public function hasAllow($permission)
    {
        $role = $this->role()->first();

        return $role->permissions()->whereName($permission)->first() ? true : false;
    }
}
