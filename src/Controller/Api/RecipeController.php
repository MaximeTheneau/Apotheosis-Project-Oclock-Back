<?php

namespace App\Controller\Api;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use App\Service\RecipeService;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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
    private $recipeService;
    private $commentRepository;
    
    public function __construct(
        RecipeRepository $recipeRepository,
        SerializerInterface $serializer,
        TokenStorageInterface $token,
        ValidatorInterface $validator,
        SluggerInterface $slugger,
        RecipeService $recipeService,
        CommentRepository $commentRepository
    ) {
        $this->recipeRepository = $recipeRepository;
        $this->serializer = $serializer;
        $this->tokenService = $token;
        $this->validator = $validator;
        $this->slugger = $slugger;
        $this->recipeService = $recipeService;
        $this->commentRepository = $commentRepository;
    }
    
    
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(): JsonResponse
    {
        $allRecipes = $this->recipeRepository->findAll();

        $this->recipeService->setEntity($allRecipes);

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

        $this->recipeService->setEntity([$recipe]);

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

        $this->recipeService->setEntity($data);

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

        $this->recipeService->setEntity($searchRecipes);

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

        
        $jsonContent = $request->request->get('json');

        try {
            $newRecipe = $this->serializer->deserialize($jsonContent, Recipe::class, 'json');
        } catch (Exception $e) {
            return $this->json400();
        }

        $this->recipeService->setRecipeIngredients($request->request->get('ingredients'), $newRecipe);

        
        $this->recipeService->setRecipeIngredients($request->request->get('ingredientsAdd'), $newRecipe);
        
        $user = $this->tokenService->getToken()->getUser();

        $newRecipe->setUser($user);
        $newRecipe->setCreatedAt(new DateTime());
        $newRecipe->setSlug($this->slugger->slug($newRecipe->getTitle()));

        // If the ingredient doesn't exist yet we need to set createdAt to add this ingredient in DataBase
        foreach ($newRecipe->getRecipeIngredients() as $RecipeIngredient) {
            $ingredient = $RecipeIngredient->getIngredient();

            // If the ingredient's id is null this ingredient is not in the Database yet
            // So we need to set the createdAt
            if (!$ingredient->getId()) {
                $ingredient->setCreatedAt(new DateTime());
            }
        }

        $errors = $this->validator->validate($newRecipe);

        if (count($errors) > 0) {
            return $this->json422($errors, $newRecipe, "api_recipes_read");
        }

        $this->recipeRepository->add($newRecipe, true);


        $this->recipeService->setPicture($newRecipe, $request, $request->files->get('picture'));

        $this->recipeRepository->add($newRecipe, true);

        return $this->json201($newRecipe, "api_recipes_read");
    }

    /**
     * @Route("/{id}", name="_edit", methods={"POST"})
     *
     */
    public function edit(Request $request, ?Recipe $recipeToUpdate)
    {
        $user = $this->tokenService->getToken()->getUser();

        if (!$this->isGranted('ROLE_USER') || $user !== $recipeToUpdate->getUser()) {
            return $this->json403();
        }

        // $jsonContent = $request->getContent();
        $jsonContent = $request->request->get('json');

        // dd($jsonContent);

        try {
            $dataToUpdate = $this->serializer->deserialize($jsonContent, Recipe::class, 'json');
        } catch (Exception $e) {
            return $this->json400();
        }

        $this->editData($dataToUpdate, $recipeToUpdate);

        if ($request->files->get('picture') || $request->request->get('deleteImage') === 'true') {
            $this->recipeService->setPicture($recipeToUpdate, $request, $request->files->get('picture'));
        }

        $recipeToUpdate->setSlug($this->slugger->slug($recipeToUpdate->getTitle()));

        $this->recipeRepository->add($recipeToUpdate, true);

        $this->recipeService->setEntity([$recipeToUpdate]);

        return $this->json200($recipeToUpdate, "api_recipes_read");
    }

    /**
     * @Route("/{id}", name="_delete", methods={"DELETE"})
     *
     */
    public function delete(?Recipe $recipeToDelete)
    {
        $user = $this->tokenService->getToken()->getUser();

        if (!$this->isGranted("ROLE_USER") || $user !== $recipeToDelete->getUser()) {
            return $this->json403();
        }

        if (!$recipeToDelete) {
            return $this->json404();
        }

        $this->recipeService->deletePicture($recipeToDelete);

        $this->recipeRepository->remove($recipeToDelete, true);

        return $this->json204();
    }

    /**
     * @Route("/user/{id}", name="_user", methods={"GET"})
     *
     */
    public function recipesByUser(?User $user)
    {
        if (!$user) {
            return $this->json404();
        }

        $recipesUser = $this->recipeRepository->findBy(
            ['user' => $user ]
        );


        $this->recipeService->setEntity($recipesUser);

        return $this->json200($recipesUser, "api_recipes_browse");
    }

    /**
     * @Route("/{id}/comments", name="_add_comment", methods={"POST"})
     *
     */
    public function addComment(Request $request, ?Recipe $recipe)
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->json403();
        }

        if (!$recipe) {
            return $this->json404();
        }

        $user = $this->tokenService->getToken()->getUser();

        $jsonContent = $request->getContent();

        try {
            $comment = $this->serializer->deserialize($jsonContent, Comment::class, 'json');
        } catch (Exception $e) {
            return $this->json400();
        }

        $comment->setUser($user);
        $comment->setCreatedAt(new DateTime());

        $recipe->addComment($comment);

        $this->recipeRepository->add($recipe, true);

        $this->recipeService->setEntity([$recipe]);

        return $this->json201($recipe, "api_recipes_read");
    }

    /**
     * @Route("/{id}/comments/{idComment}", name="_delete_comment", methods={"DELETE"})
     *
     */
    public function deleteComment(Request $request, ?Recipe $recipe, int $idComment)
    {
        $user = $this->tokenService->getToken()->getUser();

        $comment = $this->commentRepository->find($idComment);

        if (!$recipe || !$comment) {
            return $this->json404();
        }

        if (!$this->isGranted("ROLE_USER") || $user !== $comment->getUser()) {
            return $this->json403();
        }

        $recipe->removeComment($comment);

        $this->recipeService->setEntity([$recipe]);

        $this->recipeRepository->add($recipe, true);

        return $this->json204();
    }

    /**
     * @Route("/{id}/miams", name="_miams", methods={"GET"})
     *
     */
    public function miams(?Recipe $recipe)
    {
        $user = $this->tokenService->getToken()->getUser();

        if (!$this->isGranted('ROLE_USER')) {
            return $this->json403();
        }

        if (!$recipe) {
            return $this->json404();
        }

        $usersWhoFavorized = $recipe->getUsersWhoFavorized();

        $isMiam = in_array($user, $usersWhoFavorized->toArray());

        if($isMiam){
            $recipe->removeUsersWhoFavorized($user);
        } else {
            $recipe->addUsersWhoFavorized($user);
        }

        $this->recipeRepository->add($recipe, true);

        $this->recipeService->setEntity([$recipe]);

        return $this->json200($recipe, "api_recipes_read");
    }
}
