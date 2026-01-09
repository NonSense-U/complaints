<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ComplaintResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'reference_number' => $this->reference_number,
            'type' => $this->type,
            'gov_id' => $this->gov_id,
            'location' => $this->location,
            'body' => $this->body,
            'status' => $this->status,
            'notes' => $this->notes,
            'gov_name' => $this->gov ? $this->gov->name : null,
            'media' => $this->media->map(function ($m) {
                return [
                    'media_type' => $m->media_type,
                    'original_name' => $m->original_name,
                    'url' => Storage::url($m->file_path),
                ];
            })
        ];
    }
}
