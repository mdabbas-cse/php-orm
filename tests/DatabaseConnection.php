<?php

namespace Fluid\Orm\Tests;

use PDO;

use Fluid\Orm\Tests\DatabaseConnectionInterface;

class DatabaseConnection implements DatabaseConnectionInterface
{
  /**
   * @var PDO
   */
  protected $db;
  protected $credentials = [];

  /**
   * Main constructor Class
   * @param array $credentials
   * @return void
   */
  public function __construct(array $credentials)
  {
    $this->credentials = $credentials;
  }
  public function open(): PDO
  {
    try {
      $this->db = new PDO($this->credentials['driver'] . ':host=' .
        $this->credentials['connection'] . ';port=' . $this->credentials['port'] . ';dbname=' . $this->credentials['dbname'],
        $this->credentials['username'],
        $this->credentials['password'],
        $this->credentials['options']
      );
    } catch (\Exception $e) {
      throw new DatabaseConnectionException($e->getMessage(), $e->getCode(), $e);
    }
    return $this->db;
  }

  public function close(): void
  {
    $this->db = null;
  }
}