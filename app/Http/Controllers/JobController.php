<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobCollection;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = new JobCollection(Job::all());
        if(Auth::user()->contact->is_employer) {
            $jobs = new JobCollection(Job::where('user_id', Auth::user()->id)->get());
        }

        if (Auth::user()->contact->is_jobseeker) {
            $jobs = new JobCollection(Job::where('category_id', Auth::user()->contact->category_id)->get());
        }

        return $this->successResponse($jobs, 'Jobs retrieved');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'salary' => ['required', 'string', 'max:255'],
            'requirements' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all(), 'Validation Error');
        }
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['category_id'] = $request->job_category;

        $job = Job::create($data);

        return $this->successResponse($job, 'Job created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'salary' => ['nullable', 'string', 'max:255'],
            'requirements' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all(), 'Validation Error');
        }

        $data = $request->all();
        $data['category_id'] = $request->job_category;
        $job->update($data);

        return $this->successResponse($job, 'Job updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        $job->delete();

        return $this->successResponse($job, 'Job deleted');
    }
}
