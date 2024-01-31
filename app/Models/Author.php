<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToManyThrough;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function BookAuthor() : BelongsTo
    {
        return $this->belongsTo(BookAuthors::class, 'author_id', 'id');
    }
}
