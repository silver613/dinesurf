<?php

namespace App\Services\AllServices;

use Illuminate\Support\Facades\Storage;

class FileService
{
    public static function storeFile($file, $folder, $cloud = true)
    {
        if ($cloud) {
            $path = $file->store($folder, 's3');
        } else {
            $path = Storage::disk('public')->put($folder, $file);
        }

        return $path;
    }

    public static function deleteFile($path, $cloud = true)
    {
        if ($cloud) {
            Storage::cloud()->delete($path);
        } else {
            Storage::delete($path);
        }
    }
}

return new FileService;
