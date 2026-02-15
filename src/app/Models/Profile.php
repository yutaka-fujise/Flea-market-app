<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id', 'name', 'profile_image', 'postal_code', 'address', 'building',
    ];
}
