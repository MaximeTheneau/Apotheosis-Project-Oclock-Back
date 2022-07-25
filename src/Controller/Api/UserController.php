<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/user", name="app_api_user")
 */
class UserController extends ApiController
{
    private $serializer;
    private $passwordHasher;
    private $userService;
    private $userRepo;

    public function __construct(
        SerializerInterface $serializer,
        UserPasswordHasherInterface $passwordHasher,
        UserService $userService,
        UserRepository $userRepository
        )
    {
        $this->serializer = $serializer;
        $this->passwordHasher = $passwordHasher;
        $this->userService = $userService;
        $this->userRepo = $userRepository;
    }

    /**
     * @Route("", name="_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        $jsonContent = $request->request->get('json');

        // dd($jsonContent);

        try {
            $newUser = $this->serializer->deserialize($jsonContent, User::class, 'json');
        } catch (Exception $e) {
            return $this->json400();
        }

        $newUser->setPassword($this->passwordHasher->hashPassword($newUser, $newUser->getPassword()));
        $newUser->setRoles(['ROLE_USER']);
        $newUser->setCreatedAt(new DateTime());

        $this->userRepo->add($newUser, true);

        $this->userService->setPicture($newUser, $request);

        $this->userRepo->add($newUser, true);

        return $this->json201($newUser, "api_users_read");
    }
}
