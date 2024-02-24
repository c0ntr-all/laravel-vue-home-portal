<?php

namespace App\Helpers;

use App\Traits\Makeable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ImageUpload
{
    use Makeable;

    private string $folder;
    private string $diskName;
    private string $sourceName;

    private const BAD_SYMBOLS = [' ', '.'];

    public function __construct($sourceName = 'none', string $folder = 'unsorted', string $diskName = 'public')
    {
        $this->setDiskName($diskName)
             ->setFolder($folder)
             ->setSourceName($sourceName);
    }

    /**
     * @param string $folder
     * @return $this
     */
    public function setFolder(string $folder): self
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * @param string $disk
     * @return $this
     */
    public function setDiskName(string $diskName): self
    {
        $this->diskName = $diskName;

        return $this;
    }

    /**
     * @param string $sourceName
     * @return $this
     */
    public function setSourceName(string $sourceName): self
    {
        $this->sourceName = $sourceName;

        return $this;
    }

    /**
     * Сохраняет изображение, полученное из формы, в Storage
     *
     * @param $image
     * @return string
     */
    public function upload($image): string
    {
        $filename = $this->generateFileName($this->sourceName, $image->extension());

        return Storage::disk('public')->putFileAs($this->folder, $image, $filename);
    }

    /**
     * Сохраняет изображение, полученное по URL, в Storage
     *
     * @param string $url
     * @return false|string
     */
    public function uploadFromUrl(string $url): bool|string
    {
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        $fileName = $this->generateFileName($this->sourceName, $extension);
        $path = $this->folder . '/' . $fileName;

        $image = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.183 Safari/537.36',
        ])->withOptions([
            'verify' => false,
        ])->get($url)->body();

        $isSaved = Storage::disk($this->diskName)->put($path, $image);

        return $isSaved ? $path : false;
    }

    private function generateFileName(string $sourceName, string $extension): string
    {
        return str_replace(static::BAD_SYMBOLS, '_', strtolower($sourceName)) . '_' . uniqid() . '.' . $extension;
    }
}
