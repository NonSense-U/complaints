<?php

namespace App\Jobs;

use App\Models\Complaint;
use App\Models\Media;
use Cloudinary\Cloudinary;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadComplaintMediaSet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private Complaint $complaint,
        private array $files,
        private string $title,
    ) {}

    public function handle(): void
    {
        $cloudinary = $this->cloudinary ?? app(Cloudinary::class);

        foreach ($this->files as $index => $file) {
            $result = $cloudinary->UploadApi()->upload(storage_path('app/private/' . $file['path']), [
                'upload_preset' => 'complaints_media',
                'resource_type' => 'auto',
            ]);


            Media::create([
                'complaint_id' => $this->complaint->id,
                'file_path' => $result['secure_url'],
                'original_name' => $file['original_name'],
                'mime_type' => $file['mime_type'],
                'size' => $file['size'],
                'public_id' => $result['public_id'],
                'media_type' => $this->detectTypeFromMime($file['mime_type']),
            ]);
            unlink(storage_path('app/private/' . $file['path']));
        }
    }

private function detectTypeFromMime(string $mime): string
{
    return match (true) {
        str_starts_with($mime, 'image/') => 'image',
        str_starts_with($mime, 'video/') => 'video',
        str_starts_with($mime, 'audio/') => 'audio',
        default => 'document',
    };
}

}
