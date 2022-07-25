<?php

namespace App\Controller\Api;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface as StorageTokenStorageInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/recipes", name="app_api_recipes_")
 */
class RecipeController extends ApiController
{
    private $recipeRepository;
    private $serializer;
    private $tokenService;
    private $validator;
    private $slugger;
    
    public function __construct(
        RecipeRepository $recipeRepository,
        SerializerInterface $serializer,
        StorageTokenStorageInterface $token,
        ValidatorInterface $validator,
        SluggerInterface $slugger
    ) {
        $this->recipeRepository = $recipeRepository;
        $this->serializer = $serializer;
        $this->tokenService = $token;
        $this->validator = $validator;
        $this->slugger = $slugger;
    }
    
    
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(): JsonResponse
    {
        $allRecipes = $this->recipeRepository->findAll();

        return $this->json200($allRecipes, "api_recipes_browse");
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function read(?Recipe $recipe)
    {
        if ($recipe === null) {
            return $this->json404();
        }

        return $this->json200($recipe, "api_recipes_read");
    }

    /**
     * @Route("/categories/{category_id}/search", name="search_with_category_id", methods={"GET"})
     */
    public function searchWithCategoryId(int $category_id, Request $request)
    {
        $search = $request->query->get('query');

        if ($search !== "") {
            $data = $this->recipeRepository->searchWithCategory($category_id, $search);
        } else {
            $data = $this->recipeRepository->findBy(
                ['category' => $category_id]
            );
        }

        return $this->json200($data, "api_recipes_browse");
    }


    /**
     * @Route("/search", name="search", methods={"GET"})
     */
    public function search(Request $request)
    {
        $search = $request->query->get('query');

        $searchRecipes = $this->recipeRepository->search($search);
        // dd($searchRecipes);
        if ($search === "") {
            return $this->json404();
        }

        return $this->json200($searchRecipes, "api_recipes_browse");
    }

    /**
     * @Route("", name="add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->json403();
        }

        $jsonContent = $request->getContent();

        try {
            $newRecipe = $this->serializer->deserialize($jsonContent, Recipe::class, 'json');
        } catch (Exception $e) {
            return $this->json400();
        }

        $user = $this->tokenService->getToken()->getUser();

        $newRecipe->setUser($user);
        $newRecipe->setNbMiams(0);
        $newRecipe->setCreatedAt(new DateTime());
        $newRecipe->setSlug($this->slugger->slug($newRecipe->getTitle()));

        // If the ingredient doesn't exist yet we need to set createdAt to add this ingredient in DataBase
        foreach ($newRecipe->getRecipeIngredients() as $RecipeIngredient) {
            $ingredient = $RecipeIngredient->getIngredient();

            // If the ingredient's id is null this ingredient is not in the Database yet
            // So we need to set the createdAt 
            if (!$ingredient->getId()){
                $ingredient->setCreatedAt(new DateTime());
            }
        }

        $errors = $this->validator->validate($newRecipe);

        if (count($errors) > 0) {
            return $this->json422($errors, $newRecipe, "api_recipes_read");
        }

        $this->recipeRepository->add($newRecipe, true);

        return $this->json201($newRecipe, "api_recipes_read");
    }
}
