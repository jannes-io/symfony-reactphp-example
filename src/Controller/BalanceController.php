<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace App\Controller;

use App\Entity\User\Model\User;
use App\Entity\User\Repository\UserRepository;
use App\Entity\User\Transformer\UserTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BalanceController
 * @package App\Controller
 */
final class BalanceController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserTransformer
     */
    private $userTransformer;

    /**
     * BalanceController constructor.
     * @param UserRepository $userRepository
     * @param UserTransformer $userTransformer
     */
    public function __construct(UserRepository $userRepository, UserTransformer $userTransformer)
    {
        $this->userRepository = $userRepository;
        $this->userTransformer = $userTransformer;
    }

    /**
     * @Route("balance", name="api_get_balance")
     * @return JsonResponse
     */
    public function getAllBalances(): JsonResponse
    {
        return $this->getUserStateResponse();
    }

    /**
     * @Route("add-balance", name="api_add_balance")
     * @return JsonResponse
     */
    public function addBalance(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        /** @var User $user */
        foreach ($users as $user) {
            $currentBalance = $user->getBalance();
            $mod = $user->getMod();
            $user->setBalance($currentBalance + $mod);
            $this->userRepository->save($user);
        }

        return $this->getUserStateResponse();
    }

    /**
     * @Route("subtract-balance/{username}/{amount}", name="api_subtract_balance")
     * @param string $username
     * @param int $amount
     * @return JsonResponse
     */
    public function subtractBalance(string $username, int $amount): JsonResponse
    {
        /** @var User $user */
        $user = $this->userRepository->findOneByName($username);
        if ($user === null) {
            return $this->getUserStateResponse();
        }

        $user->setBalance($user->getBalance() - $amount);
        $this->userRepository->save($user);

        return $this->getUserStateResponse();
    }

    /**
     * @return JsonResponse
     */
    private function getUserStateResponse(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        return new JsonResponse(\array_map(function (User $user): array {
            return $this->userTransformer->toArray($user);
        }, $users));
    }
}