<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function index()
    {
        $companies = Company::where('is_deleted', false)->with('jobs')->get();
        return response()->json([
            'message' => 'Companies retrieved successfully.',
            'data' => CompanyResource::collection($companies)
        ]);
    }

    public function show($id)
    {
        $company = Company::with('jobs')->find($id);
        if (!$company || $company->is_deleted) {
            return response()->json([
                'message' => 'Company not found or deleted.'
            ], 404);
        }

        return response()->json([
            'message' => 'Company details retrieved successfully.',
            'data' => new CompanyResource($company)
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'industry' => 'nullable|string',
            'location' => 'nullable|string',
            'founded_year' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 400);
        }

        $company = Company::create($request->all());

        return response()->json([
            'message' => 'Company created successfully.',
            'data' => new CompanyResource($company)
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $company = Company::find($id);
        if (!$company || $company->is_deleted) {
            return response()->json([
                'message' => 'Company not found or deleted.'
            ], 404);
        }

        $company->update($request->all());

        return response()->json([
            'message' => 'Company updated successfully.',
            'data' => new CompanyResource($company)
        ]);
    }

    public function destroy($id)
    {
        $company = Company::find($id);
        if (!$company || $company->is_deleted) {
            return response()->json([
                'message' => 'Company not found or already deleted.'
            ], 404);
        }

        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully.'
        ]);
    }
}
