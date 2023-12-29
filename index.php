<?php

use Fluid\Orm\DataMapper\DataMapperEnvironmentConfiguration;
use Fluid\Orm\DataMapper\DataMapperFactory;
use Fluid\Orm\Tests\DatabaseConnection;

require_once __DIR__ . '/vendor/autoload.php';

$credentials = [
  'mysql' => [
    'driver' => 'mysql',
    'dbname' => 'quiz_app',
    'username' => 'root',
    'password' => '',
    'connection' => 'localhost',
    'port' => 3306,
    'options' => [
      PDO::ATTR_EMULATE_PREPARES => false,
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ],
  ]
];

// $database = (new DataMapperFactory)->create(DatabaseConnection::class, DataMapperEnvironmentConfiguration::class);

// if ($database) {
//   echo 'Connection successful';
// } else {
//   echo 'Connection failed';
// }

// $database->prepare('SELECT * FROM users WHERE id = :id');
// $database->bindParameters([':id' => 1]);
// $database->execute();

$data = [
  'username' => 'admin',
  'password' => 'admin',
  'email' => 'gmabbas'
];
$fieldsKey = array_keys($data);

$fields = array_map(function ($key) {
  return "`$key`";
}, $fieldsKey);
$field = implode(', ', $fields);
$placeholder = ':' . implode(', :', $fieldsKey);

print_r([$placeholder, $field, $values]);