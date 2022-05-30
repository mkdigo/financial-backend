<?php

namespace App\Exceptions;

use Throwable;
use App\Exceptions\ExceptionHandler as CustomExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
  /**
  * A list of the exception types that are not reported.
  *
  * @var array
  */
  protected $dontReport = [
    //
  ];

  /**
  * A list of the inputs that are never flashed for validation exceptions.
  *
  * @var array
  */
  protected $dontFlash = [
    'current_password',
    'password',
    'password_confirmation',
  ];

  private function response(string $message = 'Something is wrong.', int $code = 500, array $fields = null, array $errors = null)
  {
    return response()->json([
      'success' => false,
      'message' => $message,
      'fields' => $fields,
      'errors' => $errors,
    ], $code);
  }

  /**
  * Register the exception handling callbacks for the application.
  *
  * @return void
  */
  public function register()
  {
    $this->reportable(function (Throwable $e) {
      //
    });

    $this->renderable(function (CustomExceptionHandler $e, $request) {
      return $this->response($e->getMessage(), $e->getCode(), $e->getFields(), $e->getErrors());
    });

    $this->renderable(function (NotFoundHttpException $e, $request) {
      return $this->response('Record not found.', 404);
    });

    if(env('APP_DEBUG') === false) {
      $this->renderable(function (Throwable $e, $request) {
        return $this->response();
      });
    }
  }
}
