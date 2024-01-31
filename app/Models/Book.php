<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'details',
        'price',
    ];

    public function genres(): HasMany
    {
        return $this->hasMany(BookGenres::class, 'book_id', 'id');
    }

    public function authors(): HasMany
    {
        return $this->hasMany(BookAuthors::class, 'book_id', 'id');
    }

    public function genre(): HasManyThrough
    {
        return $this->hasManyThrough(Genre::class, BookGenres::class, 'book_id', 'id', 'id', 'genre_id');
    }

    public function author(): HasManyThrough
    {
        return $this->hasManyThrough(Author::class, BookAuthors::class, 'book_id', 'id', 'id', 'author_id');
    }
}
