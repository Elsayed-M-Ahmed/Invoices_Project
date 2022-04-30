<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'products'; 

    public function department() {
        return $this->belongsTo(departments::class);
    } 
}
