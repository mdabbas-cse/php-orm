<?php

declare(strict_types=1);

namespace Fluid\Orm\QueryBuilder;

use Fluid\Orm\QueryBuilder\QueryBuilderInterface;
use Fluid\Orm\QueryBuilder\Exceptions\QueryBuilderInvalidArgumentException;

class QueryBuilder implements QueryBuilderInterface
{
  /**
   * @var string $sql
   */
  private string $sql = '';

  /**
   * @const array SQL_DEFAULT
   */
  protected const SQL_DEFAULT = [
    'conditions' => [],
    'selectors' => [],
    'replace' => false,
    'distinct' => false,
    'from' => [],
    'where' => null,
    'and' => [],
    'or' => [],
    'orderby' => [],
    'fields' => [],
    'primary_key' => '',
    'table' => '',
    'type' => '',
    'raw' => '',
    'table_join' => '',
    'join_key' => '',
    'join' => []
  ];

  /**
   * @const array QUERY_TYPES
   */
  protected const QUERY_TYPES = [
    'select',
    'insert',
    'update',
    'delete',
    'raw'
  ];

  /**
   * @var array $keys
   */
  private array $keys = [];


  /**
   * Main constructor class
   * 
   * @return void
   */
  public function __construct()
  {
  }

  /*
   * buildQuery method is responsible for building the query
   *
   * @param array $args
   * @return QueryBuilderInterface
   * @throws QueryBuilderInvalidArgumentException
   */
  public function buildQuery(array $args): self
  {
    if (count($args) < 0) {
      throw new QueryBuilderInvalidArgumentException('Core Error: You have either not specify the default database driver is returning null or empty.');
    }
    $arg = array_merge(self::SQL_DEFAULT, $args);
    $this->keys = $arg;
    return $this;
  }

  private function isValidQueryType($type): bool
  {
    if (!in_array($type, self::QUERY_TYPES)) {
      throw new QueryBuilderInvalidArgumentException('Core Error: You have either not specify the default database driver is returning null or empty.');
    }
    return true;
  }

  /**
   * @inheritDoc
   *
   * @return string
   */
  public function insertQuery(): string
  {
    $this->isValidQueryType('insert');
    if (is_array($this->keys['fields']) && count($this->keys['fields']) > 0) {
      $fieldKeys = array_keys($this->keys['fields']);
      $fields = array_map(function ($key) {
        return "`$key`";
      }, $fieldKeys);
      $field = implode(', ', $fields);
      $placeholder = ':' . implode(', :', $fieldKeys);
      $this->sql = "INSERT INTO {$this->keys['table']} ({$field}) VALUES ({$placeholder})";
      return $this->sql;
    }
    return '';
  }

  /**
   * @inheritDoc
   *
   * @return string
   */
  public function selectQuery(): string
  {
    $this->isValidQueryType('select');
    $selectors = '';
    if (!empty($this->keys['selectors'])) {
      $selectors = array_map(function ($key) {
        return "`$key`";
      }, $this->keys['selectors']);
    } else {
      $selectors = '*';
    }
    $this->sql = "SELECT {$selectors} FROM {$this->keys['table']}";
    $this->sql .= $this->hasConditions();
    return $this->sql;
    // $this->sql = "SELECT ";
    // if ($this->keys['distinct']) {
    //   $this->sql .= "DISTINCT ";
    // }
    // if (is_array($this->keys['selectors']) && count($this->keys['selectors']) > 0) {
    //   $this->sql .= implode(', ', $this->keys['selectors']);
    // } else {
    //   $this->sql .= '*';
    // }
    // $this->sql .= " FROM {$this->keys['table']}";
    // if (is_array($this->keys['join']) && count($this->keys['join']) > 0) {
    //   $this->sql .= " {$this->keys['table_join']} ON {$this->keys['join_key']}";
    // }
    // if (is_array($this->keys['conditions']) && count($this->keys['conditions']) > 0) {
    //   $this->sql .= " WHERE ";
    //   $this->sql .= implode(' ', $this->keys['conditions']);
    // }
    // if (is_array($this->keys['orderby']) && count($this->keys['orderby']) > 0) {
    //   $this->sql .= " ORDER BY ";
    //   $this->sql .= implode(', ', $this->keys['orderby']);
    // }
    // return $this->sql;
  }

  /**
   * @inheritDoc
   *
   * @return string
   */
  public function updateQuery(): string
  {
    $this->isValidQueryType($this->keys['type']);
    if (is_array($this->keys['fields']) && count($this->keys['fields']) > 0) {
      $fields = [];
      foreach ($this->keys['fields'] as $field) {
        if ($field !== $this->keys['primary_key']) {
          $fields[] = "`{$field}` = :{$field}";
        }
      }
      $this->sql = "UPDATE {$this->keys['table']} SET ";
      $values = implode(', ', $fields);
      $this->sql .= $values;
      $this->sql = " WHERE {$this->keys['primary_key']} = :{$this->keys['primary_key']} LIMIT 1";
      if (isset($this->keys['primary_key']) && $this->keys['primary_key'] === '0') {
        unset($this->keys['primary_key']);
        $this->sql = "UPDATE {$this->keys['table']} SET {$values}";
      }
      $this->sql .= $this->hasConditions();
      return $this->sql;
    }
    return '';
  }

}