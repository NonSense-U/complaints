<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';
    protected $fillable = ['complaint_id','user_id','note','type'];
    public function user() { return $this->belongsTo(User::class); }
    public function complaint() { return $this->belongsTo(Complaint::class); }
}
