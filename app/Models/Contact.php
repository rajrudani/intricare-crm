<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'gender', 'profile_image', 'additional_file', 'custom_fields', 'merged_with'
    ];

    //profile imagepath
    public function getProfileImagepathAttribute()
    {
        return 'storage/' . $this->profile_image;
    }
}
