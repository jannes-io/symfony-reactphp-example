<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace App\Entity\User\Transformer;

use App\Entity\User\Model\User;

/**
 * Class UserTransformer
 * @package App\Entity\User\Transformer
 */
final class UserTransformer
{
    /**
     * @param User $user
     * @return array
     */
    public function toArray(User $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'lastname' => $user->getLastname()
        ];
    }
}