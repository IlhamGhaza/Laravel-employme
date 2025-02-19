<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Http\Resources\UserProfileResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    /**
     * Display the user profile for authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $profile = UserProfile::where('user_id', Auth::id())->firstOrFail();
        return new UserProfileResource($profile);
    }

    /**
     * Update the user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'nullable|string|max:255',
            'headline' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'about_me' => 'nullable|string|max:5000',
            'skills' => 'nullable|string|max:1000',
            'linkedin_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'website_url' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $profile = UserProfile::firstOrCreate(
            ['user_id' => Auth::id()],
            ['applied' => 0, 'reviewed' => 0, 'interview' => 0]
        );

        $profile->update($request->only([
            'full_name',
            'headline',
            'phone',
            'location',
            'about_me',
            'skills',
            'linkedin_url',
            'github_url',
            'website_url'
        ]));

        return new UserProfileResource($profile);
    }

    /**
     * Upload CV to user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadCV(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $profile = UserProfile::firstOrCreate(
            ['user_id' => Auth::id()],
            ['applied' => 0, 'reviewed' => 0, 'interview' => 0]
        );

        // Delete old CV if exists
        if ($profile->cv_path && Storage::exists('public/' . $profile->cv_path)) {
            Storage::delete('public/' . $profile->cv_path);
        }

        // Store new CV
        $path = $request->file('cv_file')->store('resumes/' . Auth::id(), 'public');
        $profile->cv_path = $path;
        $profile->save();

        return new UserProfileResource($profile);
    }
}
