<?php

declare(strict_types=1);


class DataMapper implements DataMapperInterface
{
  /**
   * @var PDO
   */
  protected $db;

  /**
   * @var PDOStatement
   */
  protected $stmt;

  /**
   * @var string
   */
  protected $sql;

  /**
   * @var array
   */
  protected $params = [];

  /**
   * @var array
   */
  protected $fields = [];

  /**
   * @var bool
   */
  protected $isSearch = false;

  /**
   * Main constructor Class
   * @param PDO $db
   * @return void
   */
  public function __construct(\PDO $db)
  {
    $this->db = $db;
  }

  /**
   * prepare a sql statement
   * 
   * @param string $sql
   * @return  self
   */
  public function prepare(string $sql): self
  {
    $this->stmt = $this->db->prepare($sql);
    return $this;
  }

  /**
   * bind a value to a parameter
   */
  public function bind(string $param, $value, int $type = null): self
  {
    if (is_null($type)) {
      switch (true) {
        case is_int($value):
          $type = \PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = \PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = \PDO::PARAM_NULL;
          break;
        default:
          $type = \PDO::PARAM_STR;
      }
    }
    $this->stmt->bindValue($param, $value, $type);
    return $this;
  }

  /**
   * bind parameters to a sql statement
   * 
   * @param array $fields
   * @param  bool $isSearch
   */
  public function bindParams(array $fields, bool $isSearch = false)
  {
    $this->fields = $fields;
    $this->isSearch = $isSearch;
    foreach ($fields as $key => $value) {
      $this->bind(":{$key}", $value);
    }
  }

  /**
   * @method numRows for counting the number of rows
   * @return int
   */
  public function numRows(): int
  {
    return $this->stmt->rowCount();
  }

  /**
   * @method execute for executing a sql statement
   */
  public function execute(): bool
  {
    return $this->stmt->execute();
  }

  /**
   * @method fetch for fetching a single record
   * @return object
   */
  public function result(): object
  {
    $this->execute();
    return $this->stmt->fetch(\PDO::FETCH_OBJ);
  }

  /**
   * @method fetchAll for fetching all records
   * @return array
   */
  public function results(): array
  {
    $this->execute();
    return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  /**
   * @method lastInsertId for getting the last inserted id
   * @return int
   */
  public function lastInsertId(): int
  {
    return $this->db->lastInsertId();
  }

  /**
   * @method beginTransaction for starting a transaction
   */
  public function beginTransaction(): bool
  {
    return $this->db->beginTransaction();
  }

  /**
   * @method endTransaction for ending a transaction
   */
  public function endTransaction(): bool
  {
    return $this->db->commit();
  }

  /**
   * @method cancelTransaction for cancelling a transaction
   */
  public function cancelTransaction(): bool
  {
    return $this->db->rollBack();
  }

  /**
   * @method debugDumpParams for debugging a sql statement
   */
  public function debugDumpParams(): void
  {
    $this->stmt->debugDumpParams();
  }

  /**
   * @method getFields for getting the fields
   * @return array
   */
  public function getFields(): array
  {
    return $this->fields;
  }

  /**
   * @method getSql for getting the sql statement
   * @return string
   */
  public function getSql(): string
  {
    return $this->sql;
  }

  /**
   * @method getParams for getting the parameters
   * @return array
   */
  public function getParams(): array
  {
    return $this->params;
  }

  /**
   * @method getIsSearch for getting the isSearch
   * @return bool
   */
  public function getIsSearch(): bool
  {
    return $this->isSearch;
  }

  /**
   * @method getStmt for getting the statement
   * @return PDOStatement
   */
  public function getStmt(): \PDOStatement
  {
    return $this->stmt;
  }
}