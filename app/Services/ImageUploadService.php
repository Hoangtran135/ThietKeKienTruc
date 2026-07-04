<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    public function upload(UploadedFile $file, string $folder): string
    {
        $path = $file->store($folder, 'uploads');

        return basename($path);
    }

    public function delete(string $folder, string $filename): void
    {
        Storage::disk('uploads')->delete($folder.'/'.$filename);
    }
}
