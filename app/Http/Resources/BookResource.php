<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'details' => $this->details,
            'price' => $this->price,
            'cover_image' => $this->cover_image,
            'author' => $this->author?->map(function ($auth) {
                return [
                    'id' => $auth->id,
                    'name' => $auth->name
                ];
            }),
            'genre' => $this->genre?->map(function ($gen) {
                return [
                    'id' => $gen->id,
                    'name' => $gen->name
                ];
            }),
            'rating' => $this->rating?->map(function ($rate) {
                return [
                    'id' => $rate->id,
                    'user_id' => $rate->user_id,
                    'rate' => $rate->rate,
                    'comment' => $rate->comment,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
