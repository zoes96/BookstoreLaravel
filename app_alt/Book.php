<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany; // namespace, der hier importiert wird, damit Model in der Klasse Book zur Verfügung steht (wird in Laravel automatisch generiert)
use App\Image;
use App\User;

class Book extends Model
{
    protected $fillable = [
        'isbn', 'title', 'subtitle', 'published', 'rating', 'description', 'user_id'
    ]; // alle beschreibbaren Properties

    public function isFavourite():bool{
        return $this->rating>5;
    }

    // Abbildung der Relation zu Images - ein Buch hat mehrere Images
    public function images():HasMany{
        return $this->hasMany(Image::class);
    }

    // Abbildung der Relation zu User - ein Buch gehört zu einem User
    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    // Abbildung der Relation zu Author
    public function authors() : BelongsToMany{
        return $this->belongsToMany(Author::class)->withTimestamps(); // damit ich zB weiß, wann ein Buch einem Autor zugeordnet wurde
    }
}
