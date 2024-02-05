<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class UserDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'address',
        'profile_image'
    ];

    public function user () : BelongsTo
    {
        return $this->BelongsTo(User::class, 'user_id', 'id');
    }
}
