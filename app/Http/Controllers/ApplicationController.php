<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{

    public function submitApplication(Request $request)
    {
        // $request->validate([
        //     'job_id' => 'required|integer',
        //     'resume' => 'required|string',
        // ]);

        $application = new Application();
        $application->job_id = $request->job_id;
        $application->user_id = Auth::user()->id;

        if($request->hasFile('resume')) {
            $path = Storage::putFile("public", $request->file("resume"));
            $path = Storage::url($path);
            $application->resume = $path;
        }
        $application->save();

        return response()->json([
            'message' => 'Application submitted successfully',
            'application' => $application,
        ]);
    }
}
