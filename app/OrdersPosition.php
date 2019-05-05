<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrdersPosition extends Model
{
    protected $fillable = [
        'amount', 'order_id', 'book_id', 'currentNettoCopy'
    ];

    // OrderPosition gehört zu genau einem Order
    public function order() : BelongsTo {
        return $this->belongsTo(Order::class);
    }

    // OrderPosition ist genau ein Buch
    public function book() : BelongsTo {
        return $this->belongsTo(Book::class);
    }
}
