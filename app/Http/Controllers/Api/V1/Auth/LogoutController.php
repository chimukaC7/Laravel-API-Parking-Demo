<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @group Auth
 *
 * Login
 */
class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        //We can access the currently logged-in user with $request->user() or auth()->user(), they are identical
        //After this call, the previous token will be deleted and no longer valid for any future requests,
        // the user would need to re-login again to get a new token.
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
