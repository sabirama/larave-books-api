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
            'author' => $this->author->map(function ($auth) {
                return [
                    'id' => $auth->id,
                    'name' => $auth->name
                ];
            }),
            'genre' => $this->genre->map(function ($gen) {
                return [
                    'id' => $gen->id,
                    'name' => $gen->name
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
