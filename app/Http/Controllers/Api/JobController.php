<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobResource;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::where('is_deleted', false)->with('company')->get();
        return response()->json([
            'message' => 'Jobs retrieved successfully.',
            'data' => JobResource::collection($jobs)
        ]);
    }

    public function show($id)
    {
        $job = Job::with('company')->find($id);
        if (!$job || $job->is_deleted) {
            return response()->json([
                'message' => 'Job not found or deleted.'
            ], 404);
        }

        return response()->json([
            'message' => 'Job details retrieved successfully.',
            'data' => new JobResource($job)
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'category' => 'required|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 400);
        }

        $job = Job::create($request->all());

        return response()->json([
            'message' => 'Job created successfully.',
            'data' => new JobResource($job)
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $job = Job::find($id);
        if (!$job || $job->is_deleted) {
            return response()->json([
                'message' => 'Job not found or deleted.'
            ], 404);
        }

        $job->update($request->all());

        return response()->json([
            'message' => 'Job updated successfully.',
            'data' => new JobResource($job)
        ]);
    }

    public function destroy($id)
    {
        $job = Job::find($id);
        if (!$job || $job->is_deleted) {
            return response()->json([
                'message' => 'Job not found or already deleted.'
            ], 404);
        }

        $job->delete();

        return response()->json([
            'message' => 'Job deleted successfully.'
        ]);
    }
}
