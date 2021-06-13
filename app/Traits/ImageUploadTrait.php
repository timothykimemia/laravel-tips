<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;

trait ImageUploadTrait
{
    protected $image_path  = "app/public/assets/posts";
    protected $img_width = 800;
    protected $img_height = null;

    public function uploadImage($request_images, $post_slug, $post)
    {
        $i = 1;

        foreach ($request_images as $file) {
            $filename = $post_slug . '-' . time() . '-' . $i . '.' . $file->getClientOriginalExtension();
            $file_size = $file->getSize();
            $file_type = $file->getMimeType();
            $path = storage_path($this->image_path . '/' . $filename);
            Image::make($file->getRealPath())->resize($this->img_width, $this->img_height, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);

            $post->media()->create([
                'file_name' => $filename,
                'file_size' => $file_size,
                'file_type' => $file_type,
            ]);

            $i++;
        }
    }
}
