<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public const IS_ADMIN = 1;
    public const IS_EDITOR = 2;
    public const IS_USER = 3;

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

}
