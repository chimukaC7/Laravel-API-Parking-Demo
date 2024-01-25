<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
//    public function register()
//    {
//        $this->reportable(function (Throwable $e) {
//            //
//        });
//
//        $this->renderable(function (Throwable $e, $request) {
//            if ($request->is('api/v1/vehicles/*')) { // <- Add your condition here
//                return response()->json([
//                    'message' => 'Vehicle record not found.'
//                ], 404);
//            }
//        });
//
//    }


//
//
//    //Laravel 8 and below:
//    public function render($request, Exception|Throwable $exception)
//    {
//        if ($request->wantsJson() || $request->is('api/*')) {
//            if ($exception instanceof ModelNotFoundException) {
//                return response()->json(['message' => 'Item Not Found'], 404);
//            }
//
//            if ($exception instanceof AuthenticationException) {
//                return response()->json(['message' => 'unAuthenticated'], 401);
//            }
//
//            if ($exception instanceof ValidationException) {
//                return response()->json(['message' => 'UnprocessableEntity', 'errors' => []], 422);
//            }
//
//            if ($exception instanceof NotFoundHttpException) {
//                return response()->json(['message' => 'The requested link does not exist'], 400);
//            }
//        }
//
//        return parent::render($request, $exception);
//    }

    private function isFrontend($request)
    {
        //check if request are coming from the web
        //it is frontend if the request accepts HTML and/or the request is a web request
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }

    //Laravel 9 and above:
    public function register()
    {
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->wantsJson() || $request->is('api/*')) {

                $modelName = strtolower(class_basename($e->getModel()));

//                return response()->json(['message' => 'Item Not Found'], 404);
                return response()->json(
                    [
                        'status' => false,
//                        'message' => 'Item Not Found',
                        'message' => "Does not exists any {$modelName} with the specified identification",
                    ], 404);
            }
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->wantsJson() || $request->is('api/*')) {

//                return response()->json(['message' => 'unAuthenticated'], 401);
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Unauthenticated'
                    ], 401);
            }
        });

        $this->renderable(function (ValidationException $e, $request) {
            if ($request->wantsJson() || $request->is('api/*')) {

                $errors = $e->validator->errors()->getMessages();

//                return response()->json(['message' => 'UnprocessableEntity', 'errors' => []], 422);
                return response()->json(
                    [
                        'status' => false,
                        'message' => "validation error",
                        'errors' => $errors,
                    ], 422);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->wantsJson() || $request->is('api/*')) {

//                return response()->json(['message' => 'The requested link does not exist'], 400);
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'The requested link does not exist'
                    ], 400);
            }
        });

        $this->renderable(function (HttpException $e, $request) {
            if ($request->wantsJson() || $request->is('api/*')) {

//                return response()->json(['message' => 'The requested link does not exist'], 400);
                return response()->json(
                    [
                        'status' => false,
                        'message' => $e->getMessage()
                    ], $e->getStatusCode());
            }
        });
    }
}
