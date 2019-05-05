<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'nettoPrice', 'bruttoPrice'
    ];

    // Order gehört zu genau einem Benutzer
    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    // Order hat mindestens eine Order-Position
    public function order_positions() : HasMany {
        return $this->hasMany(OrdersPosition::class);
    }

    // Order hat mehrere Statusse
    public function states() : HasMany {
        return $this->hasMany(States::class);
    }
}
