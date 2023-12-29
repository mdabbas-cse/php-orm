<?php

declare(strict_types=1);

namespace Fluid\Orm\DataMapper;

use Fluid\Orm\DataMapper\Exceptions\DataMapperException;
use Fluid\Orm\Tests\DatabaseConnectionInterface;
use PDO;
use PDOStatement;
use Throwable;

class DataMapper implements DataMapperInterface
{
  /**
   * @var DatabaseConnectionInterface $db
   */
  private $db;

  /**
   * @var PDOStatement
   */
  private PDOStatement $stmt;

  /**
   * Main constructor Class
   * @param DatabaseConnectionInterface $db
   * @return void
   */
  public function __construct(DatabaseConnectionInterface $db)
  {
    $this->db = $db;
  }

  /**
   * Check the incoming $value isn't empty else throw an exception
   * 
   * @param mixed $value
   * @param string|null $errorMessage
   * @return void
   * @throws DataMapperException
   */
  private function isEmpty($value, string $errorMessage = null)
  {
    if (empty($value)) {
      throw new DataMapperException($errorMessage);
    }
  }

  private function isArray($value, string $errorMessage = null)
  {
    if (!is_array($value)) {
      throw new DataMapperException($errorMessage);
    }
  }
  /**
   * @inheritDoc
   *
   * @param mixed $sql
   * @return \Fluid\Orm\DataMapper\DataMapper
   */
  public function prepare($sql): self
  {
    $this->isEmpty($sql, 'The prepare method expects a string as an argument');
    $this->stmt = $this->db->open()->prepare($sql);
    return $this;
  }

  /**
   * @inheritDoc
   *
   * @param [type] $value
   * @return int
   */
  public function bind($value): int
  {
    $dataType = PDO::PARAM_STR;
    try {
      switch ($value) {
        case is_bool($value):
        case intval($value):
          $dataType = PDO::PARAM_INT;
          break;
        case is_null($value):
          $dataType = PDO::PARAM_NULL;
          break;
        default:
          $dataType = PDO::PARAM_STR;
          break;
      }
      return $dataType;
    } catch (DataMapperException $exception) {
      throw $exception;
    }
  }

  /**
   * Binds a value to a corresponding name or question mark placeholder in the SQL
   * statement that was used to prepare the statement
   * 
   * @param array $fields
   * @return PDOStatement
   * @throws DataMapperException
   */
  protected function bindValues(array $fields): PDOStatement
  {
    $this->isArray($fields, 'The bindValues method expects an array as an argument');
    foreach ($fields as $key => $value) {
      $this->stmt->bindValue(":{$key}", $value, $this->bind($value));
    }
    return $this->stmt;
  }

  /**
   * @inheritDoc
   * 
   * @param array $fields
   * @param bool $isSearch
   * @return self
   */
  public function bindParameters(array $fields, bool $isSearch = false): self
  {
    $this->isArray($fields);
    if (is_array($fields)) {
      $type = ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);
      if ($type) {
        return $this;
      }
    }
    return false;
  }

  /**
   * Binds a value to a corresponding name or question mark placeholder
   * in the SQL statement that was used to prepare the statement. Similar to
   * above but optimized for search queries
   * 
   * @param array $fields
   * @return PDOStatement
   * @throws DataMapperException
   */
  protected function bindSearchValues(array $fields): PDOStatement
  {
    foreach ($fields as $key => $value) {
      $this->stmt->bindValue(':' . $key, '%' . $value . '%', $this->bind($value));
    }
    return $this->stmt;
  }

  /**
   * @inheritDoc
   * 
   * @return int|null
   */
  public function numRows(): int
  {
    return $this->stmt->rowCount();
  }

  /**
   * @inheritDoc
   * 
   * @return bool
   */
  public function execute(): bool
  {
    return $this->stmt->execute();
  }

  /**
   * @inheritDoc
   * 
   * @return object
   */
  public function result(): object
  {
    return $this->stmt->fetch(PDO::FETCH_OBJ);
  }

  /**
   * @inheritDoc
   * 
   * @return array
   */
  public function results(): array
  {
    return $this->stmt->fetchAll(PDO::FETCH_DEFAULT);
  }

  /**
   * @inheritDoc
   * 
   * @return mixed
   */
  public function column(): mixed
  {
    return $this->stmt->fetchColumn();
  }

  /**
   * @inheritDoc
   * 
   * @return int
   * @throws Throwable
   */
  public function getLastId(): int
  {
    try {
      if ($this->db->open()) {
        $lastId = $this->db->open()->lastInsertId();
        if (!empty($lastId)) {
          return intval($lastId);
        }
      }
    } catch (Throwable $exception) {
      throw $exception;
    }
  }

}