<?php

declare(strict_types=1);
namespace Fluid\Orm\QueryBuilder;

interface QueryBuilderInterface
{
  /**
   * Main constructor class
   * 
   * @return void
   */
  public function __construct();

  /**
   * Build query string
   * 
   * @param array $arg
   * @return QueryBuilderInterface
   */
  public function buildQuery(array $arg): QueryBuilderInterface;

  /**
   * Data insert query string
   * 
   * @return string
   */
  public function insertQuery(): string;

  /**
   * Data select query string
   * 
   * @return string
   */
  public function selectQuery(): string;

  /**
   * Data update query string
   * 
   * @return string
   */
  public function updateQuery(): string;

  /**
   * Data delete query string
   * 
   * @return string
   */
  public function deleteQuery(): string;

  /**
   * Data raw query string
   * 
   * @return string
   */
  public function rawQuery(): string;

  /**
   * Data raw query string
   * 
   * @return string
   */
  public function orderByQuery(): string;

  /**
   * Data raw query string
   * 
   * @return string
   */
  public function queryOffset(): string;

  /**
   * Data Search query string
   * 
   * @return string
   */
  public function searchQuery(): string;


  // public function truncateQuery(): string;
  // public function dropQuery(): string;
  // public function whereQuery(): string;
  // public function whereInQuery(): string;
  // public function whereNotInQuery(): string;
  // public function whereBetweenQuery(): string;
  // public function whereNotBetweenQuery(): string;
  // public function whereNullQuery(): string;
  // public function whereNotNullQuery(): string;
  // public function whereLikeQuery(): string;
  // public function whereNotLikeQuery(): string;
  // public function whereOrQuery(): string;
  // public function whereAndQuery(): string;
  // public function whereXorQuery(): string;
  // public function whereExistsQuery(): string;
  // public function whereNotExistsQuery(): string;
  // public function whereInSubQuery(): string;
  // public function whereNotInSubQuery(): string;
  // public function whereBetweenSubQuery(): string;
  // public function whereNotBetweenSubQuery(): string;
  // public function whereNullSubQuery(): string;
  // public function whereNotNullSubQuery(): string;
  // public function whereLikeSubQuery(): string;
  // public function whereNotLikeSubQuery(): string;
  // public function whereOrSubQuery(): string;
  // public function whereAndSubQuery(): string;
  // public function whereXorSubQuery(): string;
  // public function whereExistsSubQuery(): string;
  // public function whereNotExistsSubQuery(): string;

  // public function orderByQuery(): string;
  // public function groupByQuery(): string;
  // public function limitQuery(): string;
  // public function offsetQuery(): string;
  // public function joinQuery(): string;
  // public function leftJoinQuery(): string;
  // public function rightJoinQuery(): string;
  // public function fullJoinQuery(): string;
  // public function crossJoinQuery(): string;
  // public function innerJoinQuery(): string;
  // public function naturalJoinQuery(): string;
  // public function naturalLeftJoinQuery(): string;
  // public function naturalRightJoinQuery(): string;
  // public function naturalFullJoinQuery(): string;
  // public function naturalCrossJoinQuery(): string;
  // public function innerJoinSubQuery(): string;
  // public function leftJoinSubQuery(): string;
  // public function rightJoinSubQuery(): string;
  // public function fullJoinSubQuery(): string;
  // public function crossJoinSubQuery(): string;

  // public function unionQuery(): string;
  // public function unionAllQuery(): string;
  // public function intersectQuery(): string;
  // public function intersectAllQuery(): string;
  // public function exceptQuery(): string;
  // public function exceptAllQuery(): string;
  // public function subQuery(): string;
  // public function subQueryAlias(): string;
  // public function subQueryAliasAs(): string;
  // public function subQueryAliasAsSubQuery(): string;
  // public function subQueryAliasAsSubQueryAlias(): string;
  // public function subQueryAliasAsSubQueryAliasAs(): string;
  // public function subQueryAliasAsSubQueryAliasAsSubQuery(): string;

  // public function insertQueryWithValues(): string;
  // public function insertQueryWithSelect(): string;
  // public function insertQueryWithSubQuery(): string;
  // public function insertQueryWithSubQueryAlias(): string;
  // public function insertQueryWithSubQueryAliasAs(): string;

  // public function updateQueryWithValues(): string;
  // public function updateQueryWithSelect(): string;
  // public function updateQueryWithSubQuery(): string;
  // public function updateQueryWithSubQueryAlias(): string;
  // public function updateQueryWithSubQueryAliasAs(): string;

  // public function deleteQueryWithValues(): string;
  // public function deleteQueryWithSelect(): string;
  // public function deleteQueryWithSubQuery(): string;
  // public function deleteQueryWithSubQueryAlias(): string;


}