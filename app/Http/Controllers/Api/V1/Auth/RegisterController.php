<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * @group Auth
 *
 * Register
 */
class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {

        try {

            $validateUser = Validator::make($request->all(),
                [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'confirmed', Password::defaults()],
                ]
            );

            if ($validateUser->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Validation error',
                        'errors' => $validateUser->errors()
                    ],
                    401
                );
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            //we're firing a general Laravel Auth event, that could be caught with any Listeners in the future
            event(new Registered($user));

            //we have a $device variable, coming automatically from the User Agent,
            // so we're creating a token specifically for that front-end device, like a mobile phone
            $device = substr($request->userAgent() ?? '', 0, 255);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'User registered successfully',
                    'token_type' => 'Bearer',
                    'access_token' => $user->createToken($device)->plainTextToken,
                ],
                Response::HTTP_CREATED
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
