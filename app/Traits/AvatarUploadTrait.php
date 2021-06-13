<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;

trait AvatarUploadTrait
{
    protected $avatar_path = "app/public/images/covers";
    protected $avatar_height = 240;
    protected $avatar_width = 240;

    public function uploadAvatar($img)
    {
        $img_name = $this->imageName($img);

        Image::make($img)->resize($this->avatar_width, $this->avatar_height)
            ->save(storage_path($this->avatar_path.'/'.$img_name));

        return "images/covers/" . $img_name;
    }

    public function imageName($image)
    {
        return time().'-'.$image->getClientOriginalName();
    }
}
