<?php

namespace App\Controller\Api;

use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends ApiController
{

    private RecipeRepository $recipeRepo;
    private UserRepository $userRepo;
    private UserService $userService;

    public function __construct(
        RecipeRepository $recipeRepository,
        UserRepository $userRepository,
        UserService $userService
        )
    {
        $this->recipeRepo = $recipeRepository;
        $this->userRepo = $userRepository;
        $this->userService = $userService;
    }

    /**
     * @Route("/api/home", name="app_api_home")
     */
    public function index(): JsonResponse
    {
        $miamsRecipes = $this->recipeRepo->findMostMiamsRecipes();

        $lastRecipes = $this->recipeRepo->findLastRecipes();

        $randomRecipes = $this->recipeRepo->findRandomRecipes();

        // we call miamsCalcul in UserService and send an array of User Object 
        $randomUsers = $this->userService->miamsCalcul($this->userRepo->findRandomUsers());

        $data = compact(
            'miamsRecipes', 
            'lastRecipes', 
            'randomRecipes',
            'randomUsers'
        );

        return $this->json200($data, "api_recipes_browse");
    }
}
