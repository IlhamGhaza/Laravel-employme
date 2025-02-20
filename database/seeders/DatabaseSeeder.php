<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserExperience;
use App\Models\UserProfile;
use App\Models\UserPortfolio;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        \App\Models\User::factory()->create([
            'name' => 'Test Ilham ',
            'email' => 'ilham@admin.com',
            'password' => Hash::make('12345678'),
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Test user ',
            'email' => 'user@user.com',
            'password' => Hash::make('45678912'),
        ]);

        User::factory(5)->create();
        Company::factory(10)->create();
        Job::factory(20)->create();
        UserProfile::factory(5)->create();
        UserExperience::factory(5)->create();
        UserEducation::factory(5)->create();
        UserPortfolio::factory(5)->create();
        JobApplication::factory(5)->create();
        //seed
        // $this->call([
        //     CompanySeeder::class,
        //     JobSeeder::class,
        // ]);
    }
}
