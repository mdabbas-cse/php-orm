<?php

namespace Fluid\Orm\EntityManager;

use Fluid\Orm\DataMapper\DataMapperInterface;
use Fluid\Orm\EntityManager\Exceptions\CrudException;
use Fluid\Orm\QueryBuilder\QueryBuilderInterface;

class EntityManagerFactory
{

  /**
   * @var DataMapperInterface
   */
  protected DataMapperInterface $dataMapper;

  /**
   * @var QueryBuilderInterface $queryBuilder
   */
  protected QueryBuilderInterface $queryBuilder;


  /**
   * Main constructor class
   * 
   * @return void
   */
  public function __construct(DataMapperInterface $dataMapper, QueryBuilderInterface $queryBuilder)
  {
    $this->dataMapper = $dataMapper;
    $this->queryBuilder = $queryBuilder;
  }

  /**
   * Create a new entity manager instance
   *
   * @param string $crudString
   * @param string $tableSchema
   * @param string $tableSchemaID
   * @param array|null $options
   * @return EntityManager
   * @throws CrudException
   */
  public function create(string $crudString, string $tableSchema, string $tableSchemaID, ?array $options = []): EntityManager
  {
    $crudObject = new $crudString($this->dataMapper, $this->queryBuilder, $tableSchema, $tableSchemaID, $options);
    if ($crudObject instanceof CrudInterface) {
      return new EntityManager($crudObject);
    }
    throw new CrudException($crudString . ' is not an instance of CrudInterface');
  }
}