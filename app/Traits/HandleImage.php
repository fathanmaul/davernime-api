<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandleImage
{
    public function moveImageFile($imageFile, $folderName = "thumbnails"): string|null
    {
        if ($imageFile) {
            $filename = Str::uuid() . '.' . $imageFile->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs($folderName, $imageFile, $filename);
            return Storage::url($path);
        }

        return null;
    }

    protected function deleteImageIfExists($imagePath)
    {
        $relativePath = ltrim(str_replace('storage/', '', $imagePath), '/');

        if (\Storage::disk('public')->exists($relativePath)) {
            \Storage::disk('public')->delete($relativePath);
        }
    }

    public function getStoragePath(string $imageUrl)
    {
        return ltrim(str_replace('storage/', '', $imageUrl), '/');
    }
}
