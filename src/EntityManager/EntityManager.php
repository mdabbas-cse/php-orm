<?php

namespace Fluid\Orm\EntityManager;

use Fluid\Orm\EntityManager\EntityManagerInterface;
use Fluid\Orm\EntityManager\CrudInterface;

class EntityManager implements EntityManagerInterface
{
  protected CrudInterface $crud;

  /**
   * Main constructor class
   * 
   * @param CrudInterface $crud
   * 
   * @return void
   */
  public function __construct(CrudInterface $crud)
  {
    $this->crud = $crud;
  }

  /**
   * Get the crud instance
   * 
   * @return CrudInterface
   */
  public function getCrud(): object
  {
    return $this->crud;
  }

}