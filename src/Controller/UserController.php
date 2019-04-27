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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController
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
     * UserController constructor.
     * @param UserRepository $userRepository
     * @param UserTransformer $userTransformer
     */
    public function __construct(UserRepository $userRepository, UserTransformer $userTransformer)
    {
        $this->userRepository = $userRepository;
        $this->userTransformer = $userTransformer;
    }

    /**
     * @Route("/users/{id}", name="get_users", methods={"GET"}, defaults={"id"=0})
     * @param int $id
     * @return Response
     */
    public function users(int $id): Response
    {
        if ($id === 0) {
            $users = $this->userRepository->findAll();
            $arrUsers = array_map(function(User $user) {
                return $this->userTransformer->toArray($user);
            }, $users);

            return new JsonResponse($arrUsers);
        }

        $user = $this->userRepository->find($id);
        if ($user === null || !$user instanceof User) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($this->userTransformer->toArray($user));
    }

    /**
     * @Route("/users", name="create_user", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(Request $request): Response
    {
        $newUser = json_decode($request->getContent(), true);
        if (empty($newUser['name']) || empty($newUser['lastname'])) {
            return new Response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = new User($newUser['name'], $newUser['lastname']);
        $this->userRepository->save($user);
        return new JsonResponse($this->userTransformer->toArray($user));
    }

    /**
     * @Route("/users/{id}", name="create_user", methods={"POST"})
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function editUser(int $id, Request $request): Response
    {
        $user = $this->userRepository->find($id);
        if ($user === null || !$user instanceof User) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $fieldsToUpdate = json_decode($request->getContent(), true);
        if (!empty($fieldsToUpdate['name'])) {
            $user->setName($fieldsToUpdate['name']);
        }
        if (!empty($fieldsToUpdate['lastname'])) {
            $user->setName($fieldsToUpdate['lastname']);
        }
        $this->userRepository->save($user);
        return new JsonResponse($this->userTransformer->toArray($user));
    }

    /**
     * @Route("/users/{id}", name="create_user", methods={"DELETE"})
     * @param int $id
     * @return Response
     */
    public function deleteUser(int $id): Response
    {
        $user = $this->userRepository->find($id);
        if ($user === null || !$user instanceof User) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $this->userRepository->remove($user);
        return new Response('');
    }
}