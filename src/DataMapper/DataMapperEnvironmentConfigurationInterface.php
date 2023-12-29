<?php

namespace Fluid\Orm\DataMapper;

interface DataMapperEnvironmentConfigurationInterface
{

  /**
   * Get the user defined database connection array
   * 
   * @param string $driver
   * @return array
   * @throws \InvalidArgumentException
   */
  public function getDatabaseCredentials(string $driver): array;
}