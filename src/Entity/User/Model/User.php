<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace App\Entity\User\Model;

/**
 * Class User
 * @package App\Entity\User\Model
 */
class User
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int int
     */
    private $balance;

    /**
     * @var int
     */
    private $mod;

    public function __construct($name, $balance = 0, $mod = 1)
    {
        $this->name = $name;
        $this->balance = $balance;
        $this->mod = $mod;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @return int
     */
    public function getMod(): int
    {
        return $this->mod;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int $balance
     */
    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }

    /**
     * @param int $mod
     */
    public function setMod(int $mod): void
    {
        $this->mod = $mod;
    }
}