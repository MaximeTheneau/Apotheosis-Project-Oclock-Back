<?php

namespace App\Controller\Back;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Service\RecipeService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/back/recipe")
 */
class RecipeController extends AbstractController
{
    private $recipeService;
    private $slugger;

    public function __construct(RecipeService $recipeService, SluggerInterface $slugger)
    {
        $this->recipeService = $recipeService;
        $this->slugger = $slugger;
    }

    /**
     * @Route("/", name="app_back_recipe_index", methods={"GET"})
     */
    public function index(RecipeRepository $recipeRepository): Response
    {
        return $this->render('back/recipe/index.html.twig', [
            'recipes' => $recipeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_back_recipe_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RecipeRepository $recipeRepository): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $recipe->setCreatedAt(new DateTime());
            $recipe->setSlug($this->slugger->slug($recipe->getTitle()));

            $recipeRepository->add($recipe, true);

            $this->recipeService->setPicture($recipe, $request, $request->files->get('recipe')['image']);
            $recipeRepository->add($recipe, true);

            return $this->redirectToRoute('app_back_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_recipe_show", methods={"GET"})
     */
    public function show(Recipe $recipe): Response
    {
        return $this->render('back/recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_recipe_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Recipe $recipe, RecipeRepository $recipeRepository): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($request->files->get('recipe')['image'] || $request->request->get('recipe')['deleteImage']){
                $this->recipeService->setPicture($recipe, $request, $request->files->get('recipe')['image']);
            }
            
            

            $steps = $this->recipeService->editBackRecipeSteps($recipe->getSteps());
 
            $recipe->setSteps($steps);

            $recipe->setUpdatedAt(new DateTime());
            $recipe->setSlug($this->slugger->slug($recipe->getTitle()));

            $recipeRepository->add($recipe, true);

            return $this->redirectToRoute('app_back_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_recipe_delete", methods={"POST"})
     */
    public function delete(Request $request, Recipe $recipe, RecipeRepository $recipeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $this->recipeService->deletePicture($recipe);
            $recipeRepository->remove($recipe, true);
        }

        return $this->redirectToRoute('app_back_recipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
