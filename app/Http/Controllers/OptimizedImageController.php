<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class OptimizedImageController extends Controller
{
    /**
     * Create and serve a cached WebP rendition of a public article upload.
     */
    public function show(Request $request, string $path): Response
    {
        // Older uploads live directly in public/images while newer uploads are on
        // the public storage disk. Treat both consistently so old news photos do
        // not bypass WebP conversion and long-lived browser caching.
        $isPublicAsset = str_starts_with($path, 'images/');
        abort_unless($isPublicAsset || str_starts_with($path, 'articles/') || str_starts_with($path, 'media/'), 404);

        if ($isPublicAsset) {
            $imagesRoot = realpath(public_path('images'));
            $source = realpath(public_path($path));
            // Resolve the path first, then require it to remain inside public/images.
            // This permits public images only and prevents ../ path traversal.
            abort_unless(
                $imagesRoot
                && $source
                && str_starts_with($source, $imagesRoot . DIRECTORY_SEPARATOR)
                && File::isFile($source),
                404,
            );
        } else {
            $disk = Storage::disk('public');
            abort_unless($disk->exists($path), 404);
            $source = $disk->path($path);
        }
        $width = min(1600, max(160, (int) $request->integer('w', 960)));
        $cachePath = storage_path('app/image-cache/'.sha1($path.'|'.filemtime($source).'|'.$width).'.webp');

        if (! File::exists($cachePath)) {
            File::ensureDirectoryExists(dirname($cachePath));
            $this->createWebp($source, $cachePath, $width);
        }

        return response()->file($cachePath, [
            'Content-Type' => 'image/webp',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    private function createWebp(string $source, string $destination, int $targetWidth): void
    {
        $imageInfo = @getimagesize($source);
        abort_unless($imageInfo, 404);

        $image = match ($imageInfo['mime']) {
            'image/jpeg' => @imagecreatefromjpeg($source),
            'image/png' => @imagecreatefrompng($source),
            'image/webp' => @imagecreatefromwebp($source),
            'image/gif' => @imagecreatefromgif($source),
            default => null,
        };
        abort_unless($image, 415);

        $sourceWidth = imagesx($image);
        $sourceHeight = imagesy($image);
        $width = min($targetWidth, $sourceWidth);
        $height = max(1, (int) round($sourceHeight * ($width / $sourceWidth)));
        $canvas = imagecreatetruecolor($width, $height);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagefilledrectangle($canvas, 0, 0, $width, $height, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);
        imagewebp($canvas, $destination, 82);
        imagedestroy($canvas);
        imagedestroy($image);
    }
}
