<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function BookGenre() {
        return $this->belongsTo(BookGenres::class, 'genre_id', 'id');
    }
}
