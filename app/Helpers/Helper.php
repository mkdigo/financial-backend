<?php

namespace App\Helpers;

use App\Exceptions\ExceptionHandler;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator as FacadaValidator;

class Helper {
  public static function validatorErrorsToMessage(Validator $validator)
  {
    $message = '';
    $errors = $validator->errors()->all();

    foreach($errors as $error) {
      $message .= $error . ' ';
    }

    return trim($message);
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

    return [$fields, $errors];
  }

  public static function validator($data, $rules)
  {
    $validator = FacadaValidator::make($data, $rules);

    if($validator->fails()) {
      [$fields, $errors] = Helper::validatorErrors($validator);
      throw new ExceptionHandler(Helper::validatorErrorsToMessage($validator), 400, $fields, $errors);
    }
  }
}
