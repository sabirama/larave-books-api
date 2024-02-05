<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'book_id' => $this->book_id,
            'rate'=> [
                'id' => $this->id,
                'user' => [
                    'id' => $this->user?->id,
                    'username' => $this->user?->username,
                    ],
                'rating' => $this->rate,
                'comment' => $this->comment,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ]
        ];
    }
}
