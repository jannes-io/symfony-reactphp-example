<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace App\Entity\Shared\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Class AbstractRepository
 * @package App\Entity\Shared\Repository
 */
abstract class AbstractRepository
{
    protected const ENTITY = null;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * AbstractRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    /**
     * @param string $alias
     * @param string|null $indexBy
     * @return QueryBuilder
     */
    protected function createQueryBuilder(string $alias, string $indexBy = null): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder()
            ->select($alias)
            ->from($this->getEntity(), $alias, $indexBy);
    }

    /**
     * @param mixed $entity
     * @param bool $flush
     */
    public function save($entity, bool $flush = true): void
    {
        $this->entityManager->persist($entity);
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param mixed $entity
     * @param bool $flush
     */
    public function remove($entity, bool $flush = true): void
    {
        $this->entityManager->remove($entity);
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param mixed $id
     * @return object
     */
    public function find($id)
    {
        return $this->entityManager->find($this->getEntity(), $id);
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    protected function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->entityManager->getRepository($this->getEntity())->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param mixed[] $criteria The criteria.
     * @return object|null The object.
     */
    protected function findOneBy(array $criteria)
    {
        return $this->entityManager->getRepository($this->getEntity())->findOneBy($criteria);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->findBy([]);
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this::ENTITY;
    }
}