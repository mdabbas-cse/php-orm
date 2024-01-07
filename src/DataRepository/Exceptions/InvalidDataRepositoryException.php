<?php

declare(strict_types=1);

namespace Fluid\Orm\DataRepository;

class InvalidDataRepositoryException extends \Exception
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