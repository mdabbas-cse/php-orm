<?php

class DataMapperFactory
{

  /**
   * @var DataMapperEnvironmentConfigurationInterface
   */
  private DataMapperEnvironmentConfigurationInterface $environmentConfiguration;

  /**
   * @var DataMapperInterface
   */
  private DataMapperInterface $dataMapper;

  /**
   * Main construct class
   * 
   * @param DataMapperEnvironmentConfigurationInterface $environmentConfiguration
   * @param DataMapperInterface $dataMapper
   * @return void
   */
  public function __construct(DataMapperEnvironmentConfigurationInterface $environmentConfiguration, DataMapperInterface $dataMapper)
  {
    $this->environmentConfiguration = $environmentConfiguration;
    $this->dataMapper = $dataMapper;
  }

  /**
   * Get the user defined database connection array
   * 
   * @param string $driver
   * @return array
   * @throws \InvalidArgumentException
   */
  public function getDatabaseCredentials(string $driver): array
  {
    return $this->environmentConfiguration->getDatabaseCredentials($driver);
  }

  /**
   * Prepare the query string
   * 
   * @param string $sqlQuery
   * @return self
   */
  public function prepare(string $sqlQuery): self
  {
    $this->dataMapper->prepare($sqlQuery);
    return $this;
  }

  /**
   * Explicit dat type for the parameter using the PDO::PARAM_* constants.
   * 
   * @param mixed $value
   * @return int
   */
  public function bind($value): int
  {
    return $this->dataMapper->bind($value);
  }

  /**
   * Combination method which combines both methods above. One of which  is
   * optimized for binding search queries. Once the second argument $type
   * is set to search
   * 
   * @param array $fields
   * @param bool $isSearch
   * @return mixed
   */
  public function bindParameters(array $fields, bool $isSearch = false): self
  {
    $this->dataMapper->bindParameters($fields, $isSearch);
    return $this;
  }

  /**
   * returns the number of rows affected by a DELETE, INSERT, or UPDATE statement.
   * 
   * @return int|null
   */
  public function numRows(): int
  {
    return $this->dataMapper->numRows();
  }

  /**
   * Execute function which will execute the prepared statement
   *
   * @return bool
   */
  public function execute(): bool
  {
    return $this->dataMapper->execute();
  }

  /**
   * Returns a single database row as an object
   * 
   * @return object
   */
  public function result(): object
  {
    return $this->dataMapper->result();
  }

  /**
   * Returns all the rows within the database as an array
   * 
   * @return array
   */
  public function results(): array
  {
    return $this->dataMapper->results();
  }

  /**
   * Returns a database column
   * 
   * @return mixed
   */
  public function column(): mixed
  {
    return $this->dataMapper->column();
  }

  /**
   * Returns the last inserted row ID from database table
   * 
   * @return int
   * @throws Throwable
   */

  public function getLastId(): int
  {
    return $this->dataMapper->getLastId();
  }

  /**
   * Returns the number of rows affected by the last SQL statement
   * 
   * @return int
   */
  public function getAffectedRows(): int
  {
    return $this->dataMapper->getAffectedRows();
  }


}