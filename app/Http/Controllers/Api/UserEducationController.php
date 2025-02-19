<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserEducation;
use Illuminate\Http\Request;
use App\Http\Resources\UserEducationResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserEducationController extends Controller
{
    /**
     * Display a listing of the user's education.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $education = UserEducation::where('user_id', Auth::id())
            ->orderBy('end_date', 'desc')
            ->orderBy('start_date', 'desc')
            ->get();

        return UserEducationResource::collection($education);
    }

    /**
     * Store a newly created education.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'grade' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $education = UserEducation::create([
            'user_id' => Auth::id(),
            'institution' => $request->institution,
            'degree' => $request->degree,
            'field_of_study' => $request->field_of_study,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'grade' => $request->grade,
            'description' => $request->description,
        ]);

        return new UserEducationResource($education);
    }

    /**
     * Display the specified education.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $education = UserEducation::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return new UserEducationResource($education);
    }

    /**
     * Update the specified education.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'grade' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $education = UserEducation::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $education->update([
            'institution' => $request->institution,
            'degree' => $request->degree,
            'field_of_study' => $request->field_of_study,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'grade' => $request->grade,
            'description' => $request->description,
        ]);

        return new UserEducationResource($education);
    }

    /**
     * Remove the specified education.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $education = UserEducation::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $education->delete();

        return response()->json(['message' => 'Education deleted successfully']);
    }
}
