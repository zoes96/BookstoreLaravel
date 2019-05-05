<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Image;
use App\User;

class Book extends Model
{
    // alle beschreibbaren properties
    protected $fillable = [
        'isbn', 'title', 'subtitle', 'published',
        'rating', 'description', 'currentNetto', 'user_id'
    ];

    public function isFavourite() : bool {
          return $this->rating > 5;
    }

    public function images() : HasMany {
        return $this->hasMany(Image::class);
    }

    public function user() : BelongsTo  {
        return $this->belongsTo(User::class);
    }

    public function authors() : BelongsToMany {
        return $this->belongsToMany(Author::class)->withTimestamps();
    }
}
