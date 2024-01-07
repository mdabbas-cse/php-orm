<?php

declare(strict_types=1);

namespace Fluid\Orm\EntityManager;

use Fluid\Orm\DataMapper\DataMapperInterface;
use Fluid\Orm\QueryBuilder\QueryBuilderInterface;

class Crud implements CrudInterface
{
  /**
   * @var DataMapperInterface
   */
  protected DataMapperInterface $dataMapper;

  /**
   * @var QueryBuilderInterface
   */
  protected QueryBuilderInterface $queryBuilder;

  /**
   * @var string
   */
  protected string $tableSchema;

  /**
   * @var string
   */
  protected string $tableSchemaID;

  /**
   * @var array
   */
  protected array $options;



  /**
   * Main constructor class
   * 
   * @return void
   */
  public function __construct(DataMapperInterface $dataMapper, QueryBuilderInterface $queryBuilder, string $tableSchema, string $tableSchemaID, ?array $options = [])
  {
    $this->dataMapper = $dataMapper;
    $this->queryBuilder = $queryBuilder;
    $this->tableSchema = $tableSchema;
    $this->tableSchemaID = $tableSchemaID;
    $this->options = $options;
  }

  /**
   * @inheritDoc
   * 
   * @return  string
   */
  public function getSchema(): string
  {
    return $this->tableSchema;
  }

  /**
   * @inheritDoc
   * 
   * @return string
   */
  public function getSchemaID(): string
  {
    return $this->tableSchemaID;
  }

  /**
   * @inheritDoc
   * 
   * @return int
   */
  public function lastID(): int
  {
    return $this->dataMapper->getLastId();
  }

  /**
   * @inheritDoc
   * 
   * @return bool
   */
  public function create(array $fields = []): bool
  {
    try {
      $args = [
        'table' => $this->tableSchema,
        'type' => 'insert',
        'fields' => $fields
      ];
      $query = $this->queryBuilder->buildQuery($args)->insertQuery();
      $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
      if ($this->dataMapper->numRows() === 1)
        return true;
      return false;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * @inheritDoc
   * 
   * @return array
   */
  public function read(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
  {
    try {
      $args = [
        'table' => $this->getSchema(),
        'type' => 'select',
        'selectors' => $selectors,
        'conditions' => $conditions,
        'params' => $parameters,
        'extras' => $optional
      ];
      $query = $this->queryBuilder->buildQuery($args)->selectQuery();
      $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $parameters));
      return $this->dataMapper->numRows() >= 0 ? $this->dataMapper->results() : [];
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * @inheritDoc
   * 
   * @return bool
   */
  public function update(array $fields = [], string $primaryKey): bool
  {
    try {
      $args = [
        'table' => $this->getSchema(),
        'type' => 'update',
        'fields' => $fields,
        'primary_key' => $primaryKey
      ];
      $query = $this->queryBuilder->buildQuery($args)->updateQuery();
      $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
      return $this->dataMapper->numRows() == 1 ? true : false;

    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * @inheritDoc
   * 
   * @return bool
   */
  public function delete(array $conditions = []): bool
  {
    try {
      $args = [
        'table' => $this->getSchema(),
        'type' => 'delete',
        'conditions' => $conditions
      ];

      $query = $this->queryBuilder->buildQuery($args)->deleteQuery();
      $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
      return $this->dataMapper->numRows() == 1 ? true : false;

    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * @inheritDoc
   * 
   * @return ?array
   */
  public function search(array $selectors = [], array $conditions = []): ?array
  {
    try {
      $args = [
        'table' => $this->getSchema(),
        'type' => 'search',
        'selectors' => $selectors,
        'conditions' => $conditions
      ];
      $query = $this->queryBuilder->buildQuery($args)->searchQuery();
      $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
      if ($this->dataMapper->numRows() >= 0) {
        return $this->dataMapper->results();
      }
    } catch (\Throwable $throwable) {
      throw $throwable;
    }
  }

  /**
   * @inheritDoc
   *
   * @param array $selectors
   * @param array $conditions
   * @return object|null
   */
  public function get(array $selectors = [], array $conditions = []): ?object
  {
    $args = [
      'table' => $this->getSchema(),
      'type' => 'select',
      'selectors' => $selectors,
      'conditions' => $conditions
    ];
    $query = $this->queryBuilder->buildQuery($args)->selectQuery();
    $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
    if ($this->dataMapper->numRows() >= 0) {
      return $this->dataMapper->result();
    }
  }

  /**
   * @inheritDoc
   * @throws \Throwable
   */
  public function aggregate(string $type, ?string $field = 'id', array $conditions = [])
  {
    $args = [
      'table' => $this->getSchema(),
      'primary_key' => $this->getSchemaID(),
      'type' => 'select',
      'aggregate' => $type,
      'aggregate_field' => $field,
      'conditions' => $conditions
    ];

    $query = $this->queryBuilder->buildQuery($args)->selectQuery();
    $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
    if ($this->dataMapper->numRows() > 0)
      return $this->dataMapper->column();
  }

  /**
   * @inheritDoc
   * @throws \Throwable
   */
  public function countRecords(array $conditions = [], ?string $field = 'id'): int
  {
    if ($this->getSchemaID() !== '') {
      return empty($conditions) ? $this->aggregate('count', $this->getSchemaID()) : $this->aggregate('count', $this->getSchemaID(), $conditions);
    }
    return 0;
  }


}