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
    'row' => '',
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
    'row'
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

  /**
   * @inheritDoc
   *
   * @return string
   */
  public function deleteQuery(): string
  {
    if ($this->isValidQueryType('delete')) {
      $index = array_keys($this->keys['conditions']);
      $this->sql = "DELETE FROM {$this->keys['table']} WHERE {$index[0]} = :{$index[0]} LIMIT 1";
      $bulkDelete = array_values($this->keys['fields']);
      if (is_array($bulkDelete) && count($bulkDelete) > 1) {
        for ($i = 0; $i < count($bulkDelete); $i++) {
          $this->sql = "DELETE FROM {$this->keys['table']} WHERE {$index[0]} = :{$index[0]}";
        }
      }

      return $this->sql;
    }
    return '';
  }

  private function hasConditions()
  {
    if (isset($this->keys['conditions']) && $this->keys['conditions'] != '') {
      if (is_array($this->keys['conditions'])) {
        $sort = [];
        foreach (array_keys($this->keys['conditions']) as $where) {
          if (isset($where) && $where != '') {
            $sort[] = $where . " = :" . $where;
          }
        }
        if (count($this->keys['conditions']) > 0) {
          $this->sql .= " WHERE " . implode(" AND ", $sort);
        }
      }
    } else if (empty($this->keys['conditions'])) {
      $this->sql = " WHERE 1";
    }
    $this->sql .= $this->orderByQuery();
    $this->sql .= $this->queryOffset();

    return $this->sql;
  }

  /**
   * @inheritDoc
   *
   * @return string
   */
  public function rowQuery(): string
  {
    $this->isValidQueryType('row');
    if (isset($this->keys['row']) && $this->keys['row'] != '') {
      return $this->keys['row'];
    }
    return '';
  }

  /**
   * @inheritDoc
   *
   * @return string
   */
  public function orderByQuery(): string
  {
    $sql = '';
    if (is_array($this->keys['orderby']) && count($this->keys['orderby']) > 0) {
      $sql .= " ORDER BY ";
      $sql .= implode(', ', $this->keys['orderby']);
      return $sql;
    }
    return $this->sql;
  }

  /**
   * @inheritDoc
   *
   * @return string
   */
  public function queryOffset(): string
  {
    if (isset($this->keys['offset']) && $this->keys['offset'] !== '') {
      return " OFFSET {$this->keys['offset']}";
    }
    return $this->sql;
  }
}