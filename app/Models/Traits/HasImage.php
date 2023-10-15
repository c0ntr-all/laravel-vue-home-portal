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
        $rootPath = url('') . '/storage/';

        if (str_contains($this->image, 'http')) {
            return $this->image;
        } else {
            return !empty($this->image) ? $rootPath . $this->image : $rootPath . 'no-image.gif';
        }
    }
}
