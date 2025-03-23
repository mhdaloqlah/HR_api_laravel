<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'companyName','image',
        'license_number','license_release','license_expiry',
        'phone1','phone2',
        'fax','email','website','about',
        'location','address','facebook','twitter',
        'linkden','skype','whatsapp','instegram','status'     
    ];

    public function employees():HasMany{
        return $this->hasMany(Employee::class,'company_id');
    }
}
