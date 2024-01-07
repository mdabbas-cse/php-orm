<?php

namespace Fluid\Orm\EntityManager\Exceptions;

use Exception;

class CrudException extends Exception
{
  /**
   * Main constructor class
   * 
   * @return void
   */
  public function __construct(string $message)
  {
    parent::__construct($message);
  }
}