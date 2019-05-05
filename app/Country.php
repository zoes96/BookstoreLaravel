<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = [
        'name', 'tax'
    ];

    /*
    public function adresses() : BelongsToMany {
        return $this->belongsToMany(Adress::class)->withTimestamps();
    }*/

    // country has many adresses
    public function adresses() : HasMany {
        return $this->hasMany(Adress::class);
    }
}
