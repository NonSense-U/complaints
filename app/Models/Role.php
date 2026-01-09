<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Role extends Model
{
    protected $fillable = [
        'name'
        
    ];
    const ADMIN = 'admin';
    const EMPLOYEE = 'employee';
    const CITIZEN = 'citizen';
    
    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
