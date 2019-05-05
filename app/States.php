<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Order;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class States extends Model
{
    protected $fillable = [
        'name', 'comment', 'order_id'
    ];

    // State gehört zu genau einem Order
    public function order() : BelongsTo {
        return $this->belongsTo(Order::class);
    }
}
