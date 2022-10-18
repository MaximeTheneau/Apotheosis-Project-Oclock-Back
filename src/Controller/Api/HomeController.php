<?php

namespace App\Controller\Api;

use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use App\Service\RecipeService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends ApiController
{
    private $recipeRepo;
    private $userRepo;
    private $userService;
    private $mailer;
    private $recipeService;

    public function __construct(
        RecipeRepository $recipeRepository,
        UserRepository $userRepository,
        UserService $userService,
        MailerInterface $mailer,
        RecipeService $recipeService
    ) {
        $this->recipeRepo = $recipeRepository;
        $this->userRepo = $userRepository;
        $this->userService = $userService;
        $this->mailer = $mailer;
        $this->recipeService = $recipeService;
    }

    /**
     * @Route("/api/home", name="app_api_home", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $miamsRecipes = $this->recipeRepo->findMostMiamsRecipes();

        $this->recipeService->setEntity($miamsRecipes);

        $lastRecipes = $this->recipeRepo->findLastRecipes();

        $this->recipeService->setEntity($lastRecipes);

        $randomRecipes = $this->recipeRepo->findRandomRecipes();

        $this->recipeService->setEntity($randomRecipes);

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
