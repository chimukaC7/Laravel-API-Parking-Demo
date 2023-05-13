<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @group Auth
 *
 * Login
 */
class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        try {

            $validateUser = Validator::make($request->all(),
                [
                    'man_no' => ['required'],
                    'password' => ['required'],
                ]
            );

            if ($validateUser->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'validation error',
                        'errors' => $validateUser->errors()
                    ],
                    401
                );
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
//            throw ValidationException::withMessages([
//                'email' => ['The provided credentials are incorrect.'],
//            ]);
                return response()->json(
                    [
                        'message' => 'The provided credentials are incorrect'
                    ],
                    401
                );
            }

            $device = substr($request->userAgent() ?? '', 0, 255);

            //we implement the "remember me" functionality: if the $request->remember is present and true,
            // then we set the additional expiresAt parameter in the Sanctum createToken() method.
            $expiresAt = $request->remember ? null : now()->addMinutes(config('session.lifetime'));


            return response()->json(
                [
                    'status' => true,
                    'message' => 'User Logged in successfully',
                    'token_type' => 'Bearer',
//                    'access_token' => $user->createToken($device, expiresAt: $expiresAt)->plainTextToken,
                    'access_token' => $user->createToken($device)->plainTextToken,
                ],
                200
            );


        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage()
                ],
                500
            );
        }

    }
}
