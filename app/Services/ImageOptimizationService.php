<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizationService
{
    public function store(UploadedFile $file, string $directory): string
    {
        $image = extension_loaded('gd') && function_exists('imagewebp')
            ? @imagecreatefromstring($file->get())
            : false;

        if ($image === false) {
            return $file->store($directory, 'private');
        }

        $sourceWidth = imagesx($image);
        $sourceHeight = imagesy($image);
        $scale = min(1, 1600 / max($sourceWidth, $sourceHeight));
        $targetWidth = max(1, (int) round($sourceWidth * $scale));
        $targetHeight = max(1, (int) round($sourceHeight * $scale));
        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);

        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);

        $temporaryPath = tempnam(sys_get_temp_dir(), 'global-nikkah-image-');
        if ($temporaryPath === false || ! imagewebp($canvas, $temporaryPath, 82)) {
            imagedestroy($image);
            imagedestroy($canvas);

            return $file->store($directory, 'private');
        }

        $path = trim($directory, '/').'/'.Str::uuid()->toString().'.webp';
        Storage::disk('private')->put($path, file_get_contents($temporaryPath));

        imagedestroy($image);
        imagedestroy($canvas);
        unlink($temporaryPath);

        return $path;
    }
}
