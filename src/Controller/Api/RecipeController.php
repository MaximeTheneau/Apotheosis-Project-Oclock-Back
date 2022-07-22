<?php

namespace App\Controller\Api;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/recipes", name="app_api_recipes_")
 */
class RecipeController extends AbstractController
{
    private $recipeRepository;
    
    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }
    
    
    /**
     * @Route("", name="browse")
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

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(?Recipe $recipe)
    {
        if ($recipe === null) {
            return $this->json(
                [
                    "erreur" => "La recette n'existe pas",
                    "code_error" => 404
                ],
                Response::HTTP_NOT_FOUND,
            );
        }
        return $this->json(
            $recipe,
            Response::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    "api_recipes_read"
                ]
            ]
        );
    }

    /**
     * @Route("/categories/{category_id}/search", name="search_with_category_id", methods={"GET"})
     */
    public function searchWithCategoryId(int $category_id, Request $request)
    {
        $search = $request->query->get('query') ?? null;

        if ($search) {
            $data = $this->recipeRepository->searchWithCategory($category_id, $search);
        } else {
            $data = $this->recipeRepository->findBy(
                ['category' => $category_id]
            );
        }

        return $this->json(
            $data,
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