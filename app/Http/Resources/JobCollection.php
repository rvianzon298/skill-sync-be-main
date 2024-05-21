<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JobCollection extends ResourceCollection
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
                'title' => $job->title,
                'description' => $job->description,
                'location' => $job->location,
                'salary' => $job->salary,
                'requirements' => $job->requirements,
                'email' => $job->user->email,
                'company' => $job->user->contact->company,
                'fullname' => $job->user->contact->fullname,
                'position' => $job->user->contact->position,
                'contact_number' => $job->user->contact->contact_number,
                'category' => $job->category->name,
                'category_id' => $job->category->id,
                'job_already_applied' => $job->applications->contains('user_id', auth()->id()),
                'applications' => new ApplicationCollection($job->applications),
            ];
        })->all();
    }
}
