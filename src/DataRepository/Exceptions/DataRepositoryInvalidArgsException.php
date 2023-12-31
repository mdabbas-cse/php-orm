<?php

declare(strict_types=1);

namespace Fluid\Orm\DataRepository\Exceptions;

use InvalidArgumentException;
use LogicException;

class DataRepositoryInvalidArgsException extends InvalidArgumentException
{
  /**
   * Exception thrown if an argument is not of the expected type.
   *
   * @param string $message
   * @param integer $code
   * @param InvalidArgumentException $previous
   * @throws LogicException
   */
  public function __construct(string $message, int $code = 0, InvalidArgumentException $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }

}