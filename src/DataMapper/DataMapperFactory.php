<?php

declare(strict_types=1);

namespace Fluid\Orm\DataMapper;

use Fluid\Orm\DataMapper\Exceptions\DataMapperException;
use Fluid\Orm\Tests\DatabaseConnectionInterface;

class DataMapperFactory
{

  /**
   * @var $credential
   */
  private $credential;

  /**
   * Main construct class
   * 
   * @return void
   */
  public function __construct()
  {
  }

  /**
   * Get the user defined database connection array
   * 
   * @param string $databaseConnectionString
   * @param string $dataMapperConnectionEnvironmentString
   * 
   * @return DataMapperInterface
   */
  public function create(string $databaseConnectionString, string $dataMapperConnectionEnvironmentString): DataMapperInterface
  {
    $this->credential = (new $dataMapperConnectionEnvironmentString([]))->getDatabaseCredentials('mysql');
    $databaseConnectionObject = new $databaseConnectionString($this->credential);
    if (!$databaseConnectionObject instanceof DatabaseConnectionInterface) {
      throw new DataMapperException('Core Error: You have either not specify the default database driver is returning null or empty.');
    }
    return new DataMapper($databaseConnectionObject);
  }
}