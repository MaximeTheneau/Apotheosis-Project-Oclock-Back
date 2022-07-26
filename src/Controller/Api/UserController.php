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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/api/users", name="app_api_user")
 */
class UserController extends ApiController
{
    private $serializer;
    private $passwordHasher;
    private $userService;
    private $userRepo;
    private $tokenService;

    public function __construct(
        SerializerInterface $serializer,
        UserPasswordHasherInterface $passwordHasher,
        UserService $userService,
        UserRepository $userRepository,
        TokenStorageInterface $token
    ) {
        $this->serializer = $serializer;
        $this->passwordHasher = $passwordHasher;
        $this->userService = $userService;
        $this->userRepo = $userRepository;
        $this->tokenService = $token;
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

    /**
     * @Route("/{id}", name="_edit")
     *
     */
    public function edit(Request $request, ?User $userToPatch)
    {
        $user = $this->tokenService->getToken()->getUser();

        if (!$this->isGranted("ROLE_USER") || $user !== $userToPatch) {
            return $this->json403();
        }

        $jsonContent = $request->request->get('json');

        try {
            $upadtedUser = $this->serializer->deserialize($jsonContent, User::class, 'json');
        } catch (Exception $e) {
            return $this->json400();
        }

        $this->editData($upadtedUser, $userToPatch, $this->passwordHasher);

        if($request->files->get('picture')){
            $this->userService->setPicture($userToPatch, $request);
        }
        

        $this->userRepo->add($userToPatch, true);

        return $this->json200($userToPatch, "api_users_read");
    }
}
