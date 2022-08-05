<?php

namespace App\Controller\Api;

use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/ingredients", name="app_api_ingredient")
 */
class IngredientController extends ApiController
{

    private $ingredientRepo;

    public function __construct(IngredientRepository $ingredientRepository)
    {
        $this->ingredientRepo = $ingredientRepository;
    }

    /**
     * @Route("", name="_browse", methods={"GET"})
     */
    public function browse(): JsonResponse
    {
        $allIngredients = $this->ingredientRepo->findAllSort();

        return $this->json200($allIngredients, "api_ingredients_browse");
    }
}
