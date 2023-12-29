<?php

use Fluid\Orm\Tests\DatabaseConnection;

require_once __DIR__ . '/vendor/autoload.php';

$credentials = [
  'driver' => 'mysql',
  'dbname' => 'laracore',
  'username' => 'root',
  'password' => '',
  'connection' => 'localhost',
  'port' => 3306,
  'options' => [
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
  ]
];

$database = new DatabaseConnection($credentials);

if ($database->open()) {
  echo 'Connection successful';
} else {
  echo 'Connection failed';
}