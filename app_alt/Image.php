<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Book;

class Image extends Model
{
    protected $fillable = [
        'url', 'title'
    ];

    // Abbildung der Relation zum Book - ein Image gehört zu genau einem Book
    public function book():BelongsTo{
        return $this->belongsTo(Book::class);
    }
}
