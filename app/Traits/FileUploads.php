<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait FileUploads
{
    public function uploadFile(UploadedFile $file, ?int $id, string $directory, string $disk = 'local'): string
    {
        $extension = $file->getClientOriginalExtension();
        $encryptedName = Str::random(40).'.'.$extension;

        if ($id !== null) {
            $directory = $directory.'/'.$id;
        }

        return Storage::disk($disk)->putFileAs($directory, $file, $encryptedName);
    }

    public function getFilePath(string $relativePath, string $disk = 'local'): string
    {
        return Storage::disk($disk)->path($relativePath);
    }

    public function getFileUrl(?string $relativePath, ?int $id, string $disk = 'public', string $fallbackImage = 'white_img.png'): string
    {
        if ($relativePath) {
            if ($id !== null) {
                $userSpecificPath = dirname($relativePath).'/'.$id.'/'.basename($relativePath);
                if (Storage::disk($disk)->exists($userSpecificPath)) {
                    return url(Storage::url($userSpecificPath));
                }
            }

            if (Storage::disk($disk)->exists($relativePath)) {
                return url(Storage::url($relativePath));
            }
        }

        return url("assets/dashboard/defaults/{$fallbackImage}");
    }

    public function downloadFile(string $relativePath, ?string $originalName = null, string $disk = 'local'): StreamedResponse
    {
        if (! Storage::disk($disk)->exists($relativePath)) {
            abort(404, 'File not found.');
        }

        return Storage::disk($disk)->download($relativePath, $originalName);
    }

    public function deleteFile(string $relativePath, ?int $id, string $disk = 'local'): bool
    {
        if ($id !== null) {
            $idSpecificPath = dirname($relativePath).'/'.$id.'/'.basename($relativePath);
            if (Storage::disk($disk)->exists($idSpecificPath)) {
                return Storage::disk($disk)->delete($idSpecificPath);
            }
        }

        return Storage::disk($disk)->delete($relativePath);
    }

    public function getFileMimeType(string $relativePath, string $disk = 'local'): ?string
    {
        return Storage::disk($disk)->mimeType($relativePath);
    }
}
