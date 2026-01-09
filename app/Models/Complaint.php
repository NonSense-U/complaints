<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Complaint extends Model
{


    protected $fillable = [
        'user_id', 'type', 'gov_id', 'location',
        'body', 'status', 'reference_number'
    ];

    protected $casts = [
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }
    
    public function gov(): BelongsTo
    {
        return $this->belongsTo(Gov::class);
    }
    public function notes()
{
    return $this->hasMany(Note::class);
}
}


