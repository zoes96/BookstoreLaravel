<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    protected $fillable = [
        'firstName', 'lastName'
    ];

    // Abbildung der Relation zu Books
    public function books() : BelongsToMany{
        return $this->belongsToMany(Book::class)->withTimestamps(); // damit ich zB weiß, wann ein Buch einem Autor zugeordnet wurde
    }
}
