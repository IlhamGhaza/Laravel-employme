<?php

// namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;
// use App\Models\User;
// use App\Models\UserProfile;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Str;
// use App\Http\Resources\UserResource;

// class UserController extends Controller
// {
//     public function register(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'email' => 'required|string|email|max:255|unique:users',
//             'username' => 'required|string|max:255|unique:users|alpha_dash',
//             'password' => 'required|string|min:8|confirmed',
//             'full_name' => 'nullable|string|max:255',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['errors' => $validator->errors()], 422);
//         }

//         $user = User::create([
//             'email' => $request->email,
//             'username' => $request->username,
//             'password' => Hash::make($request->password),
//             'role' => 'job_seeker',
//             'verification_token' => Str::random(60),
//         ]);

//         UserProfile::create([
//             'user_id' => $user->id,
//             'full_name' => $request->full_name ?? '',
//             'applied' => 0,
//             'reviewed' => 0,
//             'interview' => 0
//         ]);

//         $token = $user->createToken('auth_token')->plainTextToken;

//         return response()->json([
//             'message' => 'User registered successfully',
//             'user' => new UserResource($user),
//             'token' => $token,
//             'token_type' => 'Bearer'
//         ], 201);
//     }

//     public function login(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'login' => 'required|string',
//             'password' => 'required|string',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['errors' => $validator->errors()], 422);
//         }

//         $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
//         $credentials = [
//             $loginField => $request->login,
//             'password' => $request->password
//         ];

//         if (!Auth::attempt($credentials)) {
//             return response()->json([
//                 'message' => 'Invalid login credentials'
//             ], 401);
//         }

//         $user = Auth::user();

//         if ($user->trashed()) {
//             Auth::logout();
//             return response()->json([
//                 'message' => 'This account has been deactivated'
//             ], 401);
//         }
//         /** @var \App\Models\User $user */

//         $token = $user->createToken('auth_token')->plainTextToken;

//         return response()->json([
//             'message' => 'Login successful',
//             'user' => new UserResource($user),
//             'token' => $token,
//             'token_type' => 'Bearer'
//         ]);
//     }

//     public function me()
//     {
//         /** @var \App\Models\User $user */
//         $user = Auth::user();
//         $user->load('userProfile');
//         return new UserResource($user);
//     }

//     public function logout(Request $request)
//     {
//         /** @var \App\Models\User $user */
//         $user = $request->user();
//         /** @var \App\Models\User $user */

//         $user->currentAccessToken()->delete();        /** @var \App\Models\User $user */


//         return response()->json(['message' => 'Successfully logged out']);
//     }

//     public function changePassword(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'current_password' => 'required|string',
//             'new_password' => 'required|string|min:8|confirmed',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['errors' => $validator->errors()], 422);
//         }

//         /** @var \App\Models\User $user */
//         $user = Auth::user();

//         if (!Hash::check($request->current_password, $user->password)) {
//             return response()->json([
//                 'message' => 'Current password is incorrect'
//             ], 401);
//         }

//         $user->password = Hash::make($request->new_password);
//         $user->save();

//         return response()->json(['message' => 'Password updated successfully']);
//     }

//     public function forgotPassword(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'email' => 'required|email|exists:users,email',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['errors' => $validator->errors()], 422);
//         }

//         /** @var \App\Models\User $user */
//         $user = User::where('email', $request->email)->first();

//         if ($user->trashed()) {
//             return response()->json([
//                 'message' => 'This account has been deactivated'
//             ], 400);
//         }

//         $token = Str::random(60);

//         $user->reset_password_token = $token;
//         $user->reset_password_expires = now()->addHours(1);
//         $user->save();

//         // TODO: Send email with reset link

//         return response()->json([
//             'message' => 'Password reset link has been sent to your email'
//         ]);
//     }

//     public function resetPassword(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'token' => 'required|string',
//             'email' => 'required|email|exists:users,email',
//             'password' => 'required|string|min:8|confirmed',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['errors' => $validator->errors()], 422);
//         }

//         /** @var \App\Models\User $user */
//         $user = User::where('email', $request->email)
//             ->where('reset_password_token', $request->token)
//             ->where('reset_password_expires', '>', now())
//             ->first();

//         if (!$user) {
//             return response()->json([
//                 'message' => 'Invalid or expired token'
//             ], 400);
//         }

//         $user->password = Hash::make($request->password);
//         $user->reset_password_token = null;
//         $user->reset_password_expires = null;
//         $user->save();

//         return response()->json([
//             'message' => 'Password has been reset successfully'
//         ]);
//     }

//     public function deactivate(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'password' => 'required|string',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['errors' => $validator->errors()], 422);
//         }

//         /** @var \App\Models\User $user */
//         $user = Auth::user();

//         if (!Hash::check($request->password, $user->password)) {
//             return response()->json([
//                 'message' => 'Password is incorrect'
//             ], 401);
//         }

//         $user->tokens()->delete();
//         $user->delete();

//         return response()->json([
//             'message' => 'Account deactivated successfully'
//         ]);
//     }
// }
