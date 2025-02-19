<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\JobResource;
use App\Http\Resources\JobCollection;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    /**
     * Display a listing of jobs with search and pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Job::with('company')->where('is_active', true);

        // Search functionality
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function (Builder $query) use ($searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhereHas('company', function (Builder $q) use ($searchTerm) {
                        $q->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by job type
        if ($request->has('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        // Filter by work arrangement
        if ($request->has('work_arrangement')) {
            $query->where('work_arrangement', $request->work_arrangement);
        }

        // Filter by location
        if ($request->has('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        // Filter by salary range
        if ($request->has('min_salary')) {
            $query->where('salary_min', '>=', $request->min_salary);
        }

        if ($request->has('max_salary')) {
            $query->where('salary_max', '<=', $request->max_salary);
        }

        // Sort
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $allowedSortFields = ['title', 'created_at', 'salary_min', 'salary_max'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->input('per_page', 10);
        $jobs = $query->paginate($perPage);

        return new JobCollection($jobs);
    }

    /**
     * Display the specified job.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = Job::with(['company'])->findOrFail($id);

        if (!$job->is_active) {
            return response()->json(['message' => 'This job is no longer active'], 404);
        }

        return new JobResource($job);
    }

    /**
     * Get related jobs based on category
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function relatedJobs($id)
    {
        $job = Job::findOrFail($id);

        $related = Job::where('category', $job->category)
            ->where('id', '!=', $id)
            ->where('is_active', true)
            ->with('company')
            ->limit(4)
            ->get();

        return JobResource::collection($related);
    }

    /**
     * Get job categories with count
     *
     * @return \Illuminate\Http\Response
     */
    public function categories()
    {
        $categories = Job::where('is_active', true)
            ->select('category')
            ->selectRaw('count(*) as job_count')
            ->groupBy('category')
            ->orderBy('job_count', 'desc')
            ->get();

        return response()->json(['data' => $categories]);
    }
}
