<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'contact_group');
    }
}
