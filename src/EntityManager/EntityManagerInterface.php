<?php

declare(strict_types=1);

namespace Fluid\Orm\EntityManager;

interface EntityManagerInterface
{
  /**
   * Main constructor class
   * 
   * @return void
   */
  public function __construct();

  public function getCrud(): object;

  // /**
  //  * Create a new entity manager instance
  //  * 
  //  * @param string $entityManagerString
  //  * @return EntityManagerInterface
  //  */
  // public function create(string $entityManagerString): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function get(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function set(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function delete(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function update(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function find(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function findAll(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function findBy(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function findOneBy(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepository(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryBy(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryByOne(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryAll(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryFind(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryFindAll(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryFindOneBy(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryFindBy(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryDelete(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryUpdate(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositorySet(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryCreate(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryInsert(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryInsertAll(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryInsertOne(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryInsertBy(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryInsertOneBy(): EntityManagerInterface;

  // /**
  //  * Get the entity manager instance
  //  * 
  //  * @return EntityManagerInterface
  //  */
  // public function getRepositoryInsertFind(): EntityManagerInterface;

}