<?php

namespace App\Http\Resources;

use App\Helpers\MediaUrlGenerator;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            
            'url' => app(MediaUrlGenerator::class)
                ->image($this->resource),

        ];
    }
}
