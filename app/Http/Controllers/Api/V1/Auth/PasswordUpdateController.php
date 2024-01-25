<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

/**
 * @group Auth
 */
class PasswordUpdateController extends Controller
{
    public function __invoke(Request $request)
    {
//        dd($request);
        $validateUser = Validator::make($request->all(),
            [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'confirmed', Password::defaults()],
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

        auth()->user()->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json(
            [
                'status' => true,
                'message' => 'Your password has been updated.',
            ],
            Response::HTTP_ACCEPTED);
    }
}
