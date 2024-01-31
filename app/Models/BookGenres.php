<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class BookGenres extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'genre_id',
    ];

    public function book() : BelongsTo
     {
        return $this->belongsTo(Book::class, 'id', 'book_id');
    }

    public function genre() : HasMany
    {
        return $this->hasMany(Genre::class, 'id', 'genre_id');
    }
}
