<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'gender', 'profile_image', 'additional_file', 'custom_fields', 'merged_with', 'merged_data'
    ];

    /**
     * Get the masterContact that owns the Contact
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function masterContact(): BelongsTo
    {
        return $this->belongsTo(self::class, 'merged_with');
    }

    // Scope - merged contacts
    public function scopeMerged($query)
    {
        return $query->whereNotNull('merged_with');
    }

    // Scope - not merged contacts
    public function scopeNotMerged($query)
    {
        return $query->whereNull('merged_with');
    }

    //Profile Imagepath
    public function getProfileImagepathAttribute()
    {
        return asset('storage/'.$this->profile_image);
    }

    //Additional Filepath
    public function getAdditionalFilepathAttribute()
    {
        return asset('storage/'.$this->additional_file);
    }
}
