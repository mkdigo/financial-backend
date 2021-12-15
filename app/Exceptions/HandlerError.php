<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class HandlerError extends Exception {
  private $test;
  private $fields;
  private $errors;

  public function __construct($message, $code = 500, $fields = null, $errors = null, Throwable $previous = null) {
    parent::__construct($message, $code, $previous);
    $this->fields = $fields;
    $this->errors = $errors;
  }

  public function getFields()
  {
    return $this->fields;
  }

  public function getErrors()
  {
    return $this->errors;
  }
}
