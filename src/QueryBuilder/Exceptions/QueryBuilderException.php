<?php

declare(strict_types=1);

namespace Fluid\Orm\QueryBuilder\Exceptions;

class QueryBuilderException extends \Exception
{
  public function __construct(string $message, int $code = 0, \Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}