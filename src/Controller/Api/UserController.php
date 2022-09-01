<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use App\Service\RecipeService;
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
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

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
    private $recipeRepository;
    private $recipeService;

    public function __construct(
        SerializerInterface $serializer,
        UserPasswordHasherInterface $passwordHasher,
        UserService $userService,
        UserRepository $userRepository,
        TokenStorageInterface $token,
        RecipeRepository $recipeRepository,
        RecipeService $recipeService
    ) {
        $this->serializer = $serializer;
        $this->passwordHasher = $passwordHasher;
        $this->userService = $userService;
        $this->userRepo = $userRepository;
        $this->tokenService = $token;
        $this->recipeRepository = $recipeRepository;
        $this->recipeService = $recipeService;
    }

    /**
     * @Route("", name="_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        $jsonContent = $request->request->get('json');

        try {
            $newUser = $this->serializer->deserialize($jsonContent, User::class, 'json');
        } catch (Exception $e) {
            return $this->json400();
        }

        $newUser->setPassword($this->passwordHasher->hashPassword($newUser, $newUser->getPassword()));
        $newUser->setRoles(['ROLE_USER']);
        $newUser->setCreatedAt(new DateTime());

        $this->userRepo->add($newUser, true);

        $this->userService->setPicture($newUser, $request, $request->files->get('picture'));

        $this->userRepo->add($newUser, true);

        return $this->json201($newUser, "api_users_add_edit");
    }

    /**
     * @Route("/{id}", name="_edit", methods={"POST"})
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

        if ($request->files->get('picture')) {
            $this->userService->setPicture($userToPatch, $request, $request->files->get('picture'));
        }
        

        $this->userRepo->add($userToPatch, true);

        return $this->json200($userToPatch, "api_users_add_edit");
    }

    /**
     * @Route("/{id}", name="_delete", methods={"DELETE"})
     *
     */
    public function delete(?User $userToDelete)
    {
        $user = $this->tokenService->getToken()->getUser();

        if (!$this->isGranted("ROLE_USER") || $user !== $userToDelete) {
            return $this->json403();
        }

        if (!$userToDelete) {
            return $this->json404();
        }

        $this->userService->deletePicture($userToDelete);

        $this->userRepo->remove($userToDelete, true);

        return $this->json204();
    }


    /**
     * @Route("/{id}", name="_read", methods={"GET"})
     *
     */
    public function read(?User $userToRead){

        $user = null;
        if($this->tokenService->getToken()){
           $user = $this->tokenService->getToken()->getUser(); 
        }
        
        if(!$userToRead){
            return $this->json404();
        }
        $data = [];

        $recipes = $this->recipeRepository->findBy(['user' => $userToRead]);

        $this->recipeService->setEntity($recipes);

        if($user !== $userToRead || !$user){
            return $this->json200($userToRead, "api_users_read");
        }elseif($user === $userToRead){

            
            $result =  $this->json200($userToRead, "api_users_read_self");

            $jsonContent = json_decode($result->getContent());

            $this->userService->selfRead($jsonContent);
            
            return new JsonResponse($jsonContent);
        }
        
    }

    /**
     * @Route("/{id}/miams", name="_read_miams_recipe", methods={"GET"})
     */
    public function recipeMiamsUser(?User $userToRead){

        $user = $this->tokenService->getToken()->getUser();

        if(!$this->isGranted('ROLE_USER') || $user !== $userToRead){
            return $this->json403();
        }

        if(!$userToRead){
            return $this->json404();
        }

        $miamsRecipes = $userToRead->getFavorites();


        $this->recipeService->setEntity($miamsRecipes->toArray());


        $result = $this->json200($miamsRecipes, "api_users_read_self");

        $jsonContent = json_decode($result->getContent());

        $this->userService->selfReadMiamsRecipes($jsonContent);

        return new JsonResponse($jsonContent);
    }
}
