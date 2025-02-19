<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Http\Resources\JobApplicationResource;
use App\Http\Resources\JobApplicationCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the job applications for current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = JobApplication::with(['job', 'job.company'])
            ->where('user_id', Auth::id());

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $allowedSortFields = ['created_at', 'status'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->input('per_page', 10);
        $applications = $query->paginate($perPage);

        return new JobApplicationCollection($applications);
    }

    /**
     * Store a newly created job application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:jobs,id',
            'cover_letter' => 'nullable|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if job is active
        $job = Job::findOrFail($request->job_id);
        if (!$job->is_active) {
            return response()->json(['message' => 'This job is no longer active'], 400);
        }

        // Check if user already applied
        $existingApplication = JobApplication::where('job_id', $request->job_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingApplication) {
            return response()->json(['message' => 'You have already applied to this job'], 400);
        }

        // Create application
        $application = JobApplication::create([
            'job_id' => $request->job_id,
            'user_id' => Auth::id(),
            'cover_letter' => $request->cover_letter,
            'status' => 'applied'
        ]);

        // Update user profile stats
        $userProfile = Auth::user()->userProfile;
        if ($userProfile) {
            $userProfile->increment('applied');
            $userProfile->save();
        }

        return new JobApplicationResource($application->load(['job', 'job.company']));
    }

    /**
     * Display the specified job application.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $application = JobApplication::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['job', 'job.company'])
            ->firstOrFail();

        return new JobApplicationResource($application);
    }

    /**
     * Update the status of a job application (withdraw).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:withdrawn',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $application = JobApplication::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($application->status === 'withdrawn') {
            return response()->json(['message' => 'Application already withdrawn'], 400);
        }

        $application->status = 'withdrawn';
        $application->save();

        return new JobApplicationResource($application->load(['job', 'job.company']));
    }

    /**
     * Remove the specified job application.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $application = JobApplication::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $application->delete();

        return response()->json(['message' => 'Application deleted successfully']);
    }
}
