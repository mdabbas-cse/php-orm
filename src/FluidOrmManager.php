<?php

declare(strict_types=1);

namespace Fluid\Orm;

use Fluid\Orm\DataMapper\DataMapperEnvironmentConfiguration;
use Fluid\Orm\DataMapper\DataMapperFactory;
use Fluid\Orm\EntityManager\Crud;
use Fluid\Orm\EntityManager\EntityManagerFactory;
use Fluid\Orm\QueryBuilder\QueryBuilder;
use Fluid\Orm\QueryBuilder\QueryBuilderFactory;
use Fluid\Orm\Tests\DatabaseConnection;

class FluidOrmManager
{
    /** @var string */
    protected string $tableSchema;

    /** @var string */
    protected string $tableSchemaID;

    /** @var array */
    protected array $options;

    /** @var DataMapperEnvironmentConfiguration */
    protected DataMapperEnvironmentConfiguration $environmentConfiguration;

    /**
     * Main class constructor
     *
     * @param DataMapperEnvironmentConfiguration $environmentConfiguration
     * @param string $tableSchema
     * @param string $tableSchemaID
     * @param array|null $options
     */
    public function __construct(DataMapperEnvironmentConfiguration $environmentConfiguration, string $tableSchema, string $tableSchemaID, ?array $options = [])
    {
        $this->environmentConfiguration = $environmentConfiguration;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
        $this->options = $options;
    }

    /**
     * initialize method which glues all the components together and inject the necessary dependency within the 
     * respective object
     *
     * @return object
     */
    public function initialize() : object
    {
        $dataMapperFactory = new DataMapperFactory();
        $dataMapper = $dataMapperFactory->create(DatabaseConnection::class, $this->environmentConfiguration);
        if ($dataMapper) {
            $queryBuilderFactory = new QueryBuilderFactory();
            $queryBuilder = $queryBuilderFactory->create(QueryBuilder::class);
            if ($queryBuilder) {
                $entityManagerFactory = new EntityManagerFactory($dataMapper, $queryBuilder);
                return $entityManagerFactory->create(Crud::class, $this->tableSchema, $this->tableSchemaID, $this->options);
            }
        }
    }

}