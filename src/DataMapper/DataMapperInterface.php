<?php

declare(strict_types=1);

namespace Fluid\Orm\DataMapper;

use Throwable;

interface DataMapperInterface
{

  /**
   * Prepare the query string
   * 
   * @param string $sqlQuery
   * @return self
   */
  public function prepare(string $sqlQuery): self;

  /**
   * Explicit dat type for the parameter using the PDO::PARAM_* constants.
   * 
   * @param mixed $value
   * @return int
   */
  public function bind($value): int;

  /**
   * Combination method which combines both methods above. One of which  is
   * optimized for binding search queries. Once the second argument $type
   * is set to search
   * 
   * @param array $fields
   * @param bool $isSearch
   * @return DataMapperInterface | bool
   */
  public function bindParameters(array $fields, bool $isSearch = false): DataMapperInterface|bool;

  /**
   * returns the number of rows affected by a DELETE, INSERT, or UPDATE statement.
   * 
   * @return int|null
   */
  public function numRows(): int;

  /**
   * Execute function which will execute the prepared statement
   * 
   * @return bool
   */
  public function execute(): bool;

  /**
   * Returns a single database row as an object
   * 
   * @return object
   */
  public function result(): object;

  /**
   * Returns all the rows within the database as an array
   * 
   * @return array
   */
  public function results(): array;

  /**
   * Returns a database column
   * 
   * @return mixed
   */
  public function column(): mixed;

  /**
   * Returns the last inserted row ID from database table
   * 
   * @return int
   * @throws Throwable
   */
  public function getLastId(): int;

  /**
   * Returns the query condition merged with the query parameters
   * 
   * @param array $conditions
   * @param array $parameters
   * @return array
   */
  public function buildQueryParameters(array $conditions = [], array $parameters = []): array;

  /**
   * Persist queries to database
   * 
   * @param string $query
   * @param array $parameters
   * @return mixed
   * @throws Throwable
   */
  public function persist(string $sqlQuery, array $parameters): mixed;
}