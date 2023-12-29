<?php

declare(strict_types=1);

namespace Fluid\Orm\QueryBuilder\Exceptions;

class QueryBuilderInvalidArgumentException extends \InvalidArgumentException
{
  public function __construct(string $message, int $code = 0, \Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}