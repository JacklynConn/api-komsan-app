<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function checkPhone(Request $request)
    {
        $phone = $request->phone;
        $user = User::where('phone', $phone)->first();
        if ($user) {
            return response()->json(['status' => 'exists'], 200);
        }
        return response()->json(['status' => '0'], 200);
    }

    public function register(Request $request)
    {
        //modified #IT-160 Mak Mach 2024-06-03
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:users',
            'password' => 'required|required|string|min:8',
            'profile_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            return response()->json(['message' => 'User already exists'], 400);
        }

        $validateData = $request->all();
        $validateData['password'] = Hash::make($validateData['password']);
        // status equals 0 means user is inactive
        $validateData['status'] = 0;
        if ($request->hasFile('profile_img')) {
            $profile = $request->file('profile_img');
            $profile_name = time() . "." . $profile->getClientOriginalExtension();
            $distinationPath = public_path('profile');
            $profile->move($distinationPath, $profile_name);
            $validateData['profile_img'] = $profile_name;
        }

        $user = User::create($validateData);
        $accessToken = $user->createToken('authToken')->accessToken;
        return response()->json(['success' => true, 'user' => $user, 'message' => 'User created successfully', 'access_token' => $accessToken], 201);
    }


    public function verify(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'phone' => 'required',
            'verification_code' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            $user->status = 1;
            $user->save();
            $accessToken = $user->createToken('authToken')->accessToken;
            return response()->json(['success' => true, 'user' => $user, 'message' => 'User verified successfully', 'access_token' => $accessToken], 200);
        }

        return response()->json(['message' => 'User not found'], 404);
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required|min:8',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            if ($user->status == 0) {
                return response()->json(['message' => 'User is not verified'], 401);
            }
            if (Hash::check($request->password, $user->password)) {
                $accessToken = $user->createToken('authToken')->accessToken;
                return response()->json(['success' => true, 'user' => $user, 'message' => 'User login successfully', 'access_token' => $accessToken], 200);
            } else {
                return response()->json(['message' => 'Invalid password'], 401);
            }
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function me()
    {
        return response(['user' => auth()->user()]);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return response(['message' => 'User logout successfully']);
    }
    // modified #IT-160 Mak Mach 2024-06-03

    public function forgetPassword(Request $request)
    {
        $request->validate(['phone' => 'required']);

        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Send verification code using Firebase
        // This will be handled on the Flutter side
        return response()->json(['message' => 'Verification code sent'], 200);
    }

    public function verifyResetCode(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'phone' => 'required',
            'verification_code' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            $user->status = 1;
            $user->save();
            return response()->json(['success' => true, 'user' => $user, 'message' => 'User verified successfully'], 200);
        }

        return response()->json(['message' => 'User not found'], 404);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required|min:8',
        ]);

        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password reset successfully'], 200);
    }
}
