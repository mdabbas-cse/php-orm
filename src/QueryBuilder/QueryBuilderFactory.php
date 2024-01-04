<?php

declare(strict_types=1);

namespace Fluid\Orm\QueryBuilder;

use Fluid\Orm\QueryBuilder\Exceptions\QueryBuilderException;
use Fluid\Orm\QueryBuilder\QueryBuilderInterface;

class QueryBuilderFactory
{
  /**
   * Main constructor class
   * 
   * @return void
   */
  public function __construct()
  {
  }

  /**
   * Create a new query builder instance
   * 
   * @param string $queryBuilderString
   * @return QueryBuilderInterface
   */
  public function create(string $queryBuilderString): QueryBuilderInterface
  {
    $queryBuilderObject = new $queryBuilderString();
    if (!$queryBuilderObject instanceof QueryBuilderInterface) {
      throw new QueryBuilderException($queryBuilderObject . ' is not an valid instance of QueryBuilderInterface');
    }
    return new QueryBuilder();
  }

}