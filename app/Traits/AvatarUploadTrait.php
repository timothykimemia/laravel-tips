<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait AvatarUploadTrait
{
    protected $avatar_path = "app/public/assets/users";
    protected $avatar_width = 300;
    protected $avatar_height = 300;

    public function uploadAvatar($avatar)
    {
        $filename = $this->avatarName($avatar);

        $path = storage_path($this->avatar_path . '/' . $filename);
        Image::make($avatar->getRealPath())->resize($this->avatar_width, $this->avatar_height, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path, 100);

        return $filename;
    }

    public function avatarName($avatar)
    {
        return Str::slug(auth()->user()->username) . '.' . $avatar->getClientOriginalExtension();
    }
}
