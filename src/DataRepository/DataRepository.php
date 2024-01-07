<?php

declare(strict_types=1);
namespace Fluid\Orm\DataRepository;

use Fluid\Orm\EntityManager\EntityManagerInterface;
use Fluid\Orm\DataRepository\Exceptions\DataRepositoryInvalidArgsException;
use Fluid\Orm\Utils\Paginator;
use Fluid\Orm\Utils\Sortable;
use InvalidArgumentException;
use Throwable;

class DataRepository implements DataRepositoryInterface
{

  /**
   * @var EntityManagerInterface
   */
  protected EntityManagerInterface $em;

  /**
   * @var array
   */
  private $_findAndReturn;
  /**
   * Main constructor class
   */
  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  private function isArray($condition = []): bool
  {
    if (!is_array($condition)) {
      throw new DataRepositoryInvalidArgsException('The supplied condition must be an array');
    }
    return true;
  }

  private function isEmpty(int $id): bool
  {
    if (empty($id)) {
      throw new DataRepositoryInvalidArgsException('The supplied id must not be empty');
    }
    return true;
  }

  /**
   * @inheritDoc
   */
  public function find(int $id): array
  {
    $this->isEmpty($id);
    try {
      return $this->findOneBy(['id' => $id]);
    } catch (\Exception $e) {
      throw new DataRepositoryInvalidArgsException($e->getMessage());
    }
  }

  /**
   * @inheritDoc
   * 
   * @return  array
   * @throws  InvalidDataRepositoryException
   */
  public function findAll(): array
  {
    try {
      return $this->em->getCrud()->read();
    } catch (Throwable $throwable) {
      throw $throwable;
    }
  }
  /**
   * @inheritDoc
   * 
   * @return  array
   * @throws  InvalidDataRepositoryException
   */
  public function findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
  {
    try {
      return $this->em->getCrud()->read($selectors, $conditions, $parameters, $optional);
    } catch (Throwable $throwable) {
      throw $throwable;
    }
  }

  /**
   * @inheritDoc
   *
   * @param array $conditions
   * @return array
   * @throws InvalidDataRepositoryException
   */
  public function findOneBy(array $conditions): array
  {
    $this->isArray($conditions);
    try {
      return $this->em->getCrud()->read([], $conditions);
    } catch (Throwable $throwable) {
      throw $throwable;
    }
  }

  /**
   * @inheritDoc
   *
   * @param array $conditions
   * @param array $selectors
   * @return Object
   */
  public function findObjectBy(array $conditions = [], array $selectors = []): object
  {
    $this->isArray($conditions);
    try {
      return $this->em->getCrud()->get($selectors, $conditions);
    } catch (Throwable $throwable) {
      throw $throwable;
    }
  }

  /**
   * @inheritDoc
   *
   * @param array $selectors
   * @param array $conditions
   * @param array $parameters
   * @param array $optional
   * @return array
   * @throws InvalidDataRepositoryException
   */
  public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
  {
    $this->isArray($conditions);
    try {
      // TODO: maybe an error in search method here. this me
      return $this->em->getCrud()->search($selectors, $conditions, $parameters, $optional);
    } catch (Throwable $throwable) {
      throw $throwable;
    }
  }

  /**
   * @inheritDoc
   *
   * @param array $conditions
   * @return bool
   * @throws InvalidDataRepositoryException
   */
  public function findByIdAndDelete(array $conditions = []): bool
  {
    $this->isArray($conditions);
    try {
      $result = $this->findOneBy($conditions);
      if ($result != null && count($result) > 0) {
        $delete = $this->em->getCrud()->delete($conditions);
        if ($delete) {
          return true;
        }
      }
      return false;
    } catch (Throwable $throwable) {
      throw $throwable;
    }
  }

  /**
   * @inheritDoc
   *
   * @param array $fields
   * @param string $primaryKey = 'id'
   * @return bool
   * @throws InvalidDataRepositoryException
   */
  public function findByIdAndUpdate(array $fields = [], int $primaryKey): bool
  {
    $this->isArray($fields);
    try {
      $result = $this->findOneBy([$this->em->getCrud()->getSchemaID() => $primaryKey]);
      if ($result !== null && count($result) > 0) {
        $params = (!empty($fields)) ? array_merge([$this->em->getCrud()->getSchemaID() => $primaryKey], $fields) : $fields;
        $update = $this->em->getCrud()->update($params, $this->em->getCrud()->getSchemaID());
        if ($update) {
          return true;
        }
      }
      return false;
    } catch (Throwable $throwable) {
      throw $throwable;
    }
  }

  /**
   * @inheritDoc
   * 
   * @param mixed $name
   * @return array
   */
  public function findWithSearchAndPaging(object $request, array $args = []): array
  {
    list($conditions, $totalRecords) = $this->getCurrentQueryStatus($request, $args);
    $sorting = new Sortable($args['sort_columns']);
    $paging = new Paginator($totalRecords, $args['records_per_page'], $request->query->getInt('page', 1));

    $parameters = ['limit' => $args['records_per_page'], 'offset' => $paging->getOffset()];
    $optional = ['orderby' => $sorting->getColumn() . ' ' . $sorting->getDirection()];

    if ($request->query->getAlnum($args['filter_alias'])) {
      $searchRequest = $request->query->getAlnum($args['filter_alias']);
      if (is_array($args['filter_by'])) {
        for ($i = 0; $i < count($args['filter_by']); $i++) {
          $searchConditions = [$args['filter_by'][$i] => $searchRequest];
        }
      }
      $results = $this->findBySearch($args['filter_by'], $searchConditions);
    } else {
      $queryConditions = array_merge($args['additional_conditions'], $conditions);
      $results = $this->findBy($args['selectors'], $queryConditions, $parameters, $optional);
    }
    return [
      $results,
      $paging->getPage(),
      $paging->getTotalPages(),
      $totalRecords,
      $sorting->sortDirection(),
      $sorting->sortDescAsc(),
      $sorting->getClass(),
      $sorting->getColumn(),
      $sorting->getDirection()
    ];
  }

  /**
   * @inheritDoc
   *
   * @param integer $id
   * @param array $selectors
   * @return self
   */
  public function findAndReturn(int $id, array $selectors = []): self
  {
    if (empty($id) || $id === 0) {
      throw new InvalidArgumentException('Please add a valid argument');
    }
    try {
      $this->_findAndReturn = $this->findObjectBy($selectors, ['id' => $id]);
      return $this;
    } catch (Throwable $throwable) {
      throw $throwable;
    }
  }
  private function getCurrentQueryStatus(object $request, array $args)
  {
    $totalRecords = 0;
    $req = $request->query;
    $status = $req->getAlnum($args['query']);
    $searchResults = $req->getAlnum($args['filter_alias']);
    if ($searchResults) {
      for ($i = 0; $i < count($args['filter_by']); $i++) {
        $conditions = [$args['filter_by'][$i] => $searchResults];
        $totalRecords = $this->em->getCrud()->countRecords($conditions, $args['filter_by'][$i]);
      }
    } else if ($status) {
      $conditions = [$args['query'] => $status];
      $totalRecords = $this->em->getCrud()->countRecords($conditions);
    } else {
      $conditions = [];
      $totalRecords = $this->em->getCrud()->countRecords($conditions);
    }
    return [
      $conditions,
      $totalRecords
    ];
  }
}