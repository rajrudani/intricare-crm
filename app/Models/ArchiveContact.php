<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveContact extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'gender', 'profile_image', 'additional_file', 'custom_fields', 'contact_id'
    ];
}
