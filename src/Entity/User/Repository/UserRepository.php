<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace App\Entity\User\Repository;

use App\Entity\Shared\Repository\AbstractRepository;
use App\Entity\User\Model\User;

/**
 * Class UserRepository
 * @package App\Entity\User\Repository
 */
final class UserRepository extends AbstractRepository
{
    protected const ENTITY = User::class;

    /**
     * @param string $name
     * @return User|null
     */
    public function findOneByName(string $name): ?User
    {
        return $this->findOneBy(['name' => $name]);
    }
}