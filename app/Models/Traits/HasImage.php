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

        return !empty($this->image) ? $rootPath . $this->image : $rootPath . 'no-image.gif';
    }
}
