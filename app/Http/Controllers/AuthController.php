<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all(), 'Validation Error');
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->successResponse([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
                'contact' => $user->contact,
                'role' => $user->contact->is_jobseeker ? 'Jobseeker' : 'Employer',
                'fullname' => $user->contact->first_name . ' ' . $user->contact->last_name,
                'profile' => $user->contact->profile_picture,
            ], 'Authenticated');
        }

        return $this->errorResponse(['Provided credentials are not correct.'], 'Unauthenticated', 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->successResponse([], 'Logged out');
    }

    public function registerJobseeker(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'required|string|max:255',
            'sex' => 'required|string|max:255',
            'birthdate' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'zipcode' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'experience' => 'nullable|string|max:255',
            'educational_attainment' => 'nullable|string|max:255',
        ]);


        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all(), 'Validation Error');
        }

        $user = User::create([
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        $data = $request->all();
        $data['is_jobseeker'] = true;
        $data['user_id'] = $user->id;
        $data['category_id'] = $request->job_category;


        if($request->hasFile('resume')) {
            $path = Storage::putFile("public", $request->file("resume"));
            $path = Storage::url($path);
            $data['resume'] = $path;
        }


        if($request->hasFile('profile_picture')) {
            $path = Storage::putFile("public", $request->file("profile_picture"));
            $path = Storage::url($path);
            $data['profile_picture'] = $path;
        }

        $contact = Contact::create($data);

        return $this->successResponse(['user' => $user, 'contact' => $contact], 'Jobseeker registered successfully');
    }

    public function registerEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            // 'extension_name' => 'required|string|max:255',
            'sex' => 'required|string|max:255',
            'birthdate' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            // 'street' => 'required|string|max:255',
            // 'barangay' => 'required|string|max:255',
            // 'province' => 'required|string|max:255',
            // 'region' => 'required|string|max:255',
            // 'country' => 'required|string|max:255',
            // 'zipcode' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);


        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all(), 'Validation Error');
        }

        $user = User::create([
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        $data = $request->all();
        $data['is_employer'] = true;
        $data['user_id'] = $user->id;

        $contact = Contact::create($data);

        return $this->successResponse(['user' => $user, 'contact' => $contact], 'Employee registered successfully');
    }

    public function updateProfileJobseeker(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|unique:users,email,' . Auth::id(),
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all(), 'Validation Error');
        }

        $user = Auth::user();
        $jobseeker = $user->contact;

        if (!$jobseeker || !$jobseeker->is_jobseeker) {
            return $this->errorResponse(['User is not a jobseeker.'], 'Unauthorized', 401);
        }

        $jobseeker->update($request->all());

        return $this->successResponse(['user' => $user, 'contact' => $jobseeker], 'Jobseeker profile updated successfully');
    }

    public function updateProfileEmployer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|unique:users,email,' . Auth::id(),
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all(), 'Validation Error');
        }

        $user = Auth::user();
        $employer = $user->contact;

        if (!$employer || !$employer->is_employer) {
            return $this->errorResponse(['User is not an employer.'], 'Unauthorized', 401);
        }

        $employer->update($request->all());

        return $this->successResponse(['user' => $user, 'contact' => $employer], 'Employer profile updated successfully');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|different:old_password',
            'confirm_new_password' => 'required|string|same:new_password',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all(), 'Validation Error');
        }

        $user = Auth::user();

        if (!Hash::check($request->input('old_password'), $user->password)) {
            return $this->errorResponse(['Current password is incorrect.'], 'Unauthorized', 401);
        }

        $user->update(['password' => bcrypt($request->input('new_password'))]);

        return $this->successResponse([], 'Password updated successfully');
    }

    public function metrics() {
        $jobCount = Job::count();
        $jobseekerCount = Contact::where('is_jobseeker', true)->count();

        return $this->successResponse([
            'job_count' => $jobCount,
            'jobseeker_count' => $jobseekerCount,
        ], 'Metrics retrieved successfully');
    }

    public function jobSeekers() {
        $jobseekers = Contact::where('is_jobseeker', true)->get();

        return $this->successResponse($jobseekers, 'Jobseekers retrieved successfully');
    }
}
