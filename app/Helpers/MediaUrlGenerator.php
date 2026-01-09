<?php

namespace App\Helpers;

use App\Models\Media;
use Cloudinary\Transformation\Resize;

class MediaUrlGenerator
{
    public function image(Media $item): string
    {
        return cloudinary()
            ->image($item->public_id)
            ->toUrl();
    }
}
