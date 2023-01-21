<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

/**
 * @group Auth
 */
class ProfileController extends Controller
{
    public function show(Request $request)
    {
        //we just show a few fields of a logged-in user (we don't show any ID or password-sensitive fields)
        return response()->json($request->user()->only('name', 'email'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name'  => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->user())],
        ]);

        auth()->user()->update($validatedData);

        return response()->json($validatedData, Response::HTTP_ACCEPTED);
    }
}
