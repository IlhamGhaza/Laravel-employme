<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserExperience;
use Illuminate\Http\Request;
use App\Http\Resources\UserExperienceResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserExperienceController extends Controller
{
    /**
     * Display a listing of the user's experiences.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $experiences = UserExperience::where('user_id', Auth::id())
            ->orderBy('is_current', 'desc')
            ->orderBy('end_date', 'desc')
            ->orderBy('start_date', 'desc')
            ->get();

        return UserExperienceResource::collection($experiences);
    }

    /**
     * Store a newly created experience.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'required|boolean',
            'description' => 'nullable|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validate that end_date is not provided if is_current is true
        if ($request->is_current && $request->filled('end_date')) {
            return response()->json([
                'errors' => ['end_date' => ['End date should not be provided for current positions']]
            ], 422);
        }

        // Validate that end_date is provided if is_current is false
        if (!$request->is_current && !$request->filled('end_date')) {
            return response()->json([
                'errors' => ['end_date' => ['End date is required for past positions']]
            ], 422);
        }

        $experience = UserExperience::create([
            'user_id' => Auth::id(),
            'company_name' => $request->company_name,
            'title' => $request->title,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_current' => $request->is_current,
            'description' => $request->description,
        ]);

        return new UserExperienceResource($experience);
    }

    /**
     * Display the specified experience.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $experience = UserExperience::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return new UserExperienceResource($experience);
    }

    /**
     * Update the specified experience.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'required|boolean',
            'description' => 'nullable|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validate that end_date is not provided if is_current is true
        if ($request->is_current && $request->filled('end_date')) {
            return response()->json([
                'errors' => ['end_date' => ['End date should not be provided for current positions']]
            ], 422);
        }

        // Validate that end_date is provided if is_current is false
        if (!$request->is_current && !$request->filled('end_date')) {
            return response()->json([
                'errors' => ['end_date' => ['End date is required for past positions']]
            ], 422);
        }

        $experience = UserExperience::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $experience->update([
            'company_name' => $request->company_name,
            'title' => $request->title,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_current' => $request->is_current,
            'description' => $request->description,
        ]);

        return new UserExperienceResource($experience);
    }

    /**
     * Remove the specified experience.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $experience = UserExperience::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $experience->delete();

        return response()->json(['message' => 'Experience deleted successfully']);
    }
}
