<?php

namespace App\Controller\Back;

use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $userRepo;

    public function __construct(UserRepository $userRepository, RecipeRepository $recipeRepository)
    {
        $this->userRepo = $userRepository;
        $this->recipeRepo = $recipeRepository;
    }

    /**
     * @Route("/back/home", name="app_back_home", methods={"GET"})
     */

    public function home(UserRepository $userRepository): Response
    {
        $nbUsers = $this->userRepo->findNbUsers();
        $nbRecipes = $this->recipeRepo->findNbRecipes();
        return $this->render('back/home.html.twig', [
        'nbUsers' => $nbUsers,
        'nbRecipes' => $nbRecipes]);
        
    }
}
