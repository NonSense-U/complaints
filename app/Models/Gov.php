<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gov extends Model
{
protected $fillable = [
        'name'
    ];
    
    public function complaint(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(User::class);
    }  //just for employees
}
