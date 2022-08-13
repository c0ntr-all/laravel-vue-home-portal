<?php

namespace App\Models\Traits;

trait HasImage
{
    /**
     * Аксессор на получение абсолютного пути до изображения
     *
     * @return string
     */
    public function getFullImageAttribute(): string
    {
        $rootPath = env('APP_URL') . '/storage/';

        if (!empty($this->image)) {
            return $rootPath . $this->image;
        } else {
            return $rootPath . 'no-image.gif';
        }
    }
}
