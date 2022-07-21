<?php

namespace App\Controller\Api;

use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    private $recipeRepository;
    
    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }
    
    
    /**
     * @Route("/api/recipes", name="app_api_recipes")
     */
    public function browse(): JsonResponse
    {
        $allRecipes = $this->recipeRepository->findAll();
        return $this->json(
            $allRecipes,
            Response::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    "api_recipes_browse"
                ]
            ]
        );
    }
}
