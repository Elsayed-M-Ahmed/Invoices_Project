<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class departments extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_name' ,
        'description' ,
        'created_by'
    ];

    protected $table = 'departments'; 
    protected $primaryKey = 'id';

public function products(){
        return $this->hasMany(products::class);
    }

    public function invoices(){
        return $this->hasMany(invoices::class);
    }    

}
