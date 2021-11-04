<?php

namespace App\Helpers;

use Illuminate\Validation\Validator;

class ResponseHelper {
  public static function validatorErrorsToMessage(Validator $validator)
  {
    $message = '';
    $errors = $validator->errors()->all();

    foreach($errors as $error) {
      $message .= $error . ' ';
    }

    return response()->json([
      'success' => false,
      'message' => trim($message),
    ], 400);
  }

  public static function validatorErrors(Validator $validator)
  {
    $errors = [];
    $fields = [];

    foreach($validator->errors()->getMessages() as $key => $errorArray) {
      $message = '';
      foreach($errorArray as $error){
        $message .= $error . ' ';
      }
      $errors[$key] = trim($message);
      $fields[] = $key;
    }

    return response()->json([
      'success' => false,
      'fields' => $fields,
      'errors' => $errors,
    ], 400);
  }

  public static function unauthorized()
  {
    return response()->json([
      'success' => false,
      'message' => 'Unauthorized'
    ], 401);
  }

  public static function badResquest($message)
  {
    return response()->json([
      'success' => false,
      'message' => $message ? $message : 'Bad Request.'
    ], 400);
  }

  public static function notFound($message)
  {
    return response()->json([
      'success' => false,
      'message' => $message ? $message : 'Bad Request.'
    ], 404);
  }
}
