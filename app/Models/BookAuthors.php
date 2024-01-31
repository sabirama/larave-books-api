<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookAuthors extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'author_id',
    ];

    public function book() : BelongsTo
     {
        return $this->belongsTo(Book::class,'id', 'book_id');
    }

    public function author() : hasMany
     {
        return $this->hasMany(Author::class,'id', 'author_id');
    }
}
