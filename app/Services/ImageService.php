<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected string $disk = 'public';

    public function upload(UploadedFile $file, string $folder): string
    {
        $filename = $folder . '/' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        Storage::disk($this->disk)->put($filename, file_get_contents($file->getRealPath()));
        return $filename;
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->delete($path);
        }
    }

    public function getUrl(?string $path): ?string
    {
        if ($path && Storage::disk($this->disk)->exists($path)) {
            return Storage::url($path);
        }

        return null;
    }
}
