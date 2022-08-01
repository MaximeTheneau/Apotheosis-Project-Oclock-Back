<?php

namespace App\Controller\Back;

use App\Entity\RecipeIngredient;
use App\Form\RecipeIngredientType;
use App\Repository\RecipeIngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/groceries")
 */
class RecipeIngredientController extends AbstractController
{
    /**
     * @Route("/", name="app_back_recipe_ingredient_index", methods={"GET"})
     */
    public function index(RecipeIngredientRepository $recipeIngredientRepository): Response
    {
        return $this->render('back/recipe_ingredient/index.html.twig', [
            'recipe_ingredients' => $recipeIngredientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_back_recipe_ingredient_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RecipeIngredientRepository $recipeIngredientRepository): Response
    {
        $recipeIngredient = new RecipeIngredient();
        $form = $this->createForm(RecipeIngredientType::class, $recipeIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipeIngredientRepository->add($recipeIngredient, true);

            return $this->redirectToRoute('app_back_recipe_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/recipe_ingredient/new.html.twig', [
            'recipe_ingredient' => $recipeIngredient,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_recipe_ingredient_show", methods={"GET"})
     */
    public function show(RecipeIngredient $recipeIngredient): Response
    {
        return $this->render('back/recipe_ingredient/show.html.twig', [
            'recipe_ingredient' => $recipeIngredient,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_recipe_ingredient_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, RecipeIngredient $recipeIngredient, RecipeIngredientRepository $recipeIngredientRepository): Response
    {
        $form = $this->createForm(RecipeIngredientType::class, $recipeIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipeIngredientRepository->add($recipeIngredient, true);

            return $this->redirectToRoute('app_back_recipe_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/recipe_ingredient/edit.html.twig', [
            'recipe_ingredient' => $recipeIngredient,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_recipe_ingredient_delete", methods={"POST"})
     */
    public function delete(Request $request, RecipeIngredient $recipeIngredient, RecipeIngredientRepository $recipeIngredientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipeIngredient->getId(), $request->request->get('_token'))) {
            $recipeIngredientRepository->remove($recipeIngredient, true);
        }

        return $this->redirectToRoute('app_back_recipe_ingredient_index', [], Response::HTTP_SEE_OTHER);
    }
}
