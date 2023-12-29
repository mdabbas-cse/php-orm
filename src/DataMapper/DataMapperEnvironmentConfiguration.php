<?php

declare(strict_types=1);

namespace Fluid\Orm\DataMapper;

class DataMapperEnvironmentConfiguration implements DataMapperEnvironmentConfigurationInterface
{
  /**
   * @var array
   */
  private array $credentials;

  /**
   * Main construct class
   * 
   * @param array $credentials
   * @return void
   */
  public function __construct(array $credentials)
  {
    $this->credentials = $credentials;
  }

  /**
   * Checks credentials for validity
   * 
   * @param string $driver
   * @return void
   */
  private function isValidateDriver(string $driver): void
  {
    if (empty($driver) || !is_array($this->credentials)) {
      throw new \InvalidArgumentException('Core Error: You have either not specify the default database driver is returning null or empty.');
    }
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
    if ($this->isValidateDriver($driver)) {
      return $this->credentials[$driver];
    }
    return [];
  }

}