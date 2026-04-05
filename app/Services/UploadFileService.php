<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UploadFileService
{
    /**
     * Upload a file with a unique name, optionally deleting old file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @param string|null $oldFilePath
     * @return string $path
     */
    public function upload($file, $folder = 'uploads', $oldFilePath = null)
    {
        if (!$file) {
            return null; // No file provided
        }

        // Generate a unique file name
        $filename = time() . '_' . Str::random(8) . '_' . $file->getClientOriginalName();

        // Delete old file if it exists
        if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
            Storage::disk('public')->delete($oldFilePath);
        }

        // Store new file
        $path = $file->storeAs($folder, $filename, 'public');

        return $path;
    }
}