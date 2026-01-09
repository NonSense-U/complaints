<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{

    protected $fillable = [
        'complaint_id',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'media_type'
    ];

        public function complaint()
{
    return $this->belongsTo(Complaint::class);
}
}



