<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\String_;

class UploadImageService
{
    /**
     * Сохраняет изображение, полученное из формы, в Storage
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function uploadFromForm($image, $name = 'none', $folder = 'unsorted'): string
    {
        try{
            $name = str_replace(' ', '_', $name) . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs($folder, $image, $name);

            return $path;
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }

    public function uploadFromFolder($image, $name = 'none', $folder = 'unsorted'): string
    {
        try{
            $name = str_replace(' ', '_', $name) . '_' . uniqid() . '.' . $image->extension();
            $path = Storage::disk('public')->putFileAs($folder, $image, $name);

            return $path;
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }
}
