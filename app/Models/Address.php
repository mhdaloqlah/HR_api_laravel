<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;
    protected $fillable = [
        'address_name',       
    ];

    public function employees():HasMany{
        return $this->hasMany(Employee::class,'address_incompany_id');
    }

    

}
