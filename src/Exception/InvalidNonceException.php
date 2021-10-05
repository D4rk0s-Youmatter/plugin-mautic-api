<?php

namespace D4rk0s\WpMauticApi\Exception;

use Exception;
use Throwable;

class InvalidNonceException extends Exception
{
  public function __construct($message = "Security check failed", $code = 403, Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}