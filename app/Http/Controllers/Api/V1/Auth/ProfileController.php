<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * @group Auth
 */
class ProfileController extends Controller
{
    public function show(Request $request)
    {
        //we just show a few fields of a logged-in user (we don't show any ID or password-sensitive fields)
        return response()->json(
            [
                'status' => true,
                'data' => $request->user()->only('name', 'email')
            ],
            Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:5'],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore(auth()->user())],
        ], [
            'name.required' => 'Name is must.',
            'name.min' => 'Name must have 5 char.',
        ]);

        if ($validate->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ],
                401
            );
        }


        auth()->user()->update($validate->getData());

        return response()->json(
            [
                'status' => true,
                'message' => "Saved successfully",
                'data' => $validate->getData(),
            ],
            Response::HTTP_ACCEPTED);
    }
}
