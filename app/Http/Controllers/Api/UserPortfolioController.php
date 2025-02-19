<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserPortfolio;
use Illuminate\Http\Request;
use App\Http\Resources\UserPortfolioResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserPortfolioController extends Controller
{
    /**
     * Display a listing of the user's portfolio projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $portfolios = UserPortfolio::where('user_id', Auth::id())
            ->orderBy('end_date', 'desc')
            ->orderBy('start_date', 'desc')
            ->get();

        return UserPortfolioResource::collection($portfolios);
    }

    /**
     * Store a newly created portfolio project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'project_url' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'technologies' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $portfolioData = [
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'project_url' => $request->project_url,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'technologies' => $request->technologies,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('portfolios/' . Auth::id(), 'public');
            $portfolioData['image_path'] = $path;
        }

        $portfolio = UserPortfolio::create($portfolioData);

        return new UserPortfolioResource($portfolio);
    }

    /**
     * Display the specified portfolio project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $portfolio = UserPortfolio::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return new UserPortfolioResource($portfolio);
    }

    /**
     * Update the specified portfolio project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'project_url' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'technologies' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $portfolio = UserPortfolio::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $portfolioData = [
            'title' => $request->title,
            'description' => $request->description,
            'project_url' => $request->project_url,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'technologies' => $request->technologies,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($portfolio->image_path && Storage::exists('public/' . $portfolio->image_path)) {
                Storage::delete('public/' . $portfolio->image_path);
            }

            // Store new image
            $path = $request->file('image')->store('portfolios/' . Auth::id(), 'public');
            $portfolioData['image_path'] = $path;
        }

        $portfolio->update($portfolioData);

        return new UserPortfolioResource($portfolio);
    }

    /**
     * Remove the specified portfolio project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $portfolio = UserPortfolio::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Delete image if exists
        if ($portfolio->image_path && Storage::exists('public/' . $portfolio->image_path)) {
            Storage::delete('public/' . $portfolio->image_path);
        }

        $portfolio->delete();

        return response()->json(['message' => 'Portfolio project deleted successfully']);
    }
}
