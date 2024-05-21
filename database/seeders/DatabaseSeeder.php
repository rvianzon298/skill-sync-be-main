<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);



        $user = User::create([
            'email' => 'admin@superuser.com',
            'password' => bcrypt('123'),
        ]);


        Contact::create([
            'first_name' => 'Admin',
            'last_name' => 'Superuser',
            'middle_name' => 'Middle',
            'sex' => 'Male',
            'birthdate' => '2024-05-05',
            'address' => '123 Admin Street',
            'contact_number' => '1234567890',
            'company' => 'Admin Company',
            'position' => 'Admin Position',
            'user_id' => $user->id,
            'is_employer' => true,
        ]);






        Category::create([
            'name' => 'Office Work',
        ]);
        Category::create([
            'name' => 'Production',
        ]);

        Category::create([
            'name' => 'Skilled',
        ]);

        Category::create([
            'name' => 'Hospitality',
        ]);

        Job::create([
            'title' => 'Admin Job',
            'description' => 'Admin Job Description',
            'category_id' => 1,
            'location' => 'Admin Location',
            'salary' => 'Admin Salary',
            'user_id' => $user->id,
            'requirements' => 'Admin Requirements',
        ]);

        Job::create([
            'title' => 'Admin Job 2',
            'description' => 'Admin Job Description 2',
            'category_id' => 2,
            'location' => 'Admin Location 2',
            'salary' => 'Admin Salary 2',
            'user_id' => $user->id,
            'requirements' => 'Admin Requirements 2',
        ]);

        Job::create([
            'title' => 'Admin Job 3',
            'description' => 'Admin Job Description 3',
            'category_id' => 3,
            'location' => 'Admin Location 3',
            'salary' => 'Admin Salary 3',
            'user_id' => $user->id,
            'requirements' => 'Admin Requirements 3',
        ]);


        Job::create([
            'title' => 'Admin Job 4',
            'description' => 'Admin Job Description 4',
            'category_id' => 4,
            'location' => 'Admin Location 4',
            'salary' => 'Admin Salary 4',
            'user_id' => $user->id,
            'requirements' => 'Admin Requirements 4',
        ]);

        $user2 = User::create([
            'email' => 'seeker@sample.com',
            'password' => bcrypt('123'),
        ]);

        Contact::create(
            [
                'first_name' => 'Makmak',
                'last_name' => 'Due',
                'middle_name' => 'Parungao',
                'extension_name' => 'II',
                'sex' => 'Male',
                'birthdate' => '2024-05-05',
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
                'user_id' => $user2->id,
                'is_jobseeker' => true,
                'category_id' => 1,
            ]
        );

    }
}
