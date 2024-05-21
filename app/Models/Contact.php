<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'extension_name',
        'sex',
        'birthdate',
        'address',
        'street',
        'barangay',
        'province',
        'region',
        'country',
        'zipcode',
        'contact_number',
        'experience',
        'educational_attainment',
        'company',
        'position',
        'is_employer',
        'is_jobseeker',
        'is_admin',
        'profile_picture',
        'user_id',
        'category_id',
        'resume',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
