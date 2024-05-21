<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApplicationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($job) {
            return [
                'id' => $job->id,
                'resume' => $job->resume,
                'name' => $job->user->contact->fullname,
            ];
        })->all();
    }
}
