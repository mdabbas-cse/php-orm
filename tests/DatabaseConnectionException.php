<?php

namespace Fluid\Orm\Tests;

use Exception;
use Throwable;

class DatabaseConnectionException extends Exception
{
  public function __construct(string $message, int $code = 0, Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}