<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        try 
        {
            $request->validated();
            $public_id = User::generateUniquePublicID();
            
            $password = Hash::make($request->password);
            $user = User::create([
                'password' => $password,
                'name' => $request->name,
                'email' => $request->email,
                'last_name' => $request->last_name,
                'public_id' => $public_id
            ]);
        } 
        catch (\Throwable $th) 
        {
            abort(400, $th->getMessage());
        }

        return response()->json($user);
    }

    /**
     * @param LoginRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try 
        {
            $request->validated();
            $credentials = $request->getCredentials();

            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'message' => 'Given data is invalid'
                ]);
            }

            $user = User::where('email', $request->email)->first();
            $authToken = $user->createToken('auth-token')->plainTextToken;
        } 
        catch (\Throwable $th) 
        {
            abort(400, $th->getMessage());
        }

        return response()->json([
            'access_token' => $authToken
        ]);
    }
}