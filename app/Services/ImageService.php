<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public static function compress($file, $folder = 'permintaan')
    {
        $manager = new ImageManager(new Driver());

        $image = $manager->read($file->getRealPath())
            ->orient(); // fix rotasi iPhone

        // Resize jika terlalu besar
        if ($image->width() > 1600) {
            $image->scale(width: 1600);
        }

        $filename = uniqid() . '.webp';

        $compressed = $image->toWebp(75);

        Storage::disk('public')->put($folder . '/' . $filename, $compressed);

        return $folder . '/' . $filename;
    }
}
