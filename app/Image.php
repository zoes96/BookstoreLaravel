<?php

namespace App;

use App\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $fillable = [
      'url', 'title'
    ];

    public function book() : BelongsTo {
        return $this->belongsTo(Book::class);
    }
}
