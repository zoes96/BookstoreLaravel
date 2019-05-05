<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Adress extends Model
{
    protected $fillable = [
        'street', 'postcode', 'city', 'country_id'
    ];

    // adress has many user
    public function user() : HasMany {
        return $this->hasMany(User::class)->withTimestamps();
    }

    // adress belongs to exactly 1 country
    public function country() : BelongsTo {
        return $this->belongsTo(Country::class);
    }
}
