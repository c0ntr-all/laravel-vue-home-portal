<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\String_;

class UploadImageService
{
    /**
     * Upload the image for Artists posters
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function upload($image, $name = 'none'): string
    {
        try{
            $name = str_replace(' ', '_', $name) . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs('music/artists/posters', $image, $name);

            return $path;
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }
}
