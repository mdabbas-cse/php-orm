<?php

declare(strict_types=1);

namespace Fluid\Orm\DataRepository;

use Fluid\Orm\DataMapper\DataMapperEnvironmentConfiguration;
use Fluid\Orm\DataRepository\Exceptions\DataUnexpectedValueException;
use Fluid\Orm\FluidOrmManager;

class DataRepositoryFactory
{
  /**
   * @var string
   */
  private $crudIdentifier;

  /**
   * @var string
   */
  private $tableSchema;

  /**
   * @var string
   */
  private $tableSchemaID;

  /**
   * Main class constructor
   *
   * @param string $crudIdentifier
   * @param string $tableSchema
   * @param string $tableSchemaID
   */
  public function __construct(string $crudIdentifier, string $tableSchema, string $tableSchemaID)
  {
    $this->crudIdentifier = $crudIdentifier;
    $this->tableSchema = $tableSchema;
    $this->tableSchemaID = $tableSchemaID;
  }

  /**
   * Create the DataRepository Object
   *
   * @param string $dataRepositoryString
   * @return void
   * @throws DataUnexpectedValueException
   */
  public function create(string $dataRepositoryString): DataRepositoryInterface
  {
    $entityManager = $this->initializeFluidOrmManager();
    $dataRepositoryObject = new $dataRepositoryString($entityManager);
    if (!$dataRepositoryObject instanceof DataRepositoryInterface) {
      throw new DataUnexpectedValueException($dataRepositoryString . ' is not a valid repository object');
    }
    return $dataRepositoryObject;
  }

  public function initializeFluidOrmManager()
  {
    $environmentConfiguration = new DataMapperEnvironmentConfiguration([
      'driver' => 'mysql',
      'host' => 'localhost',
      'port' => '3306',
    ]);
    $ormManager = new FluidOrmManager($environmentConfiguration, $this->tableSchema, $this->tableSchemaID);
    return $ormManager->initialize();
  }

}