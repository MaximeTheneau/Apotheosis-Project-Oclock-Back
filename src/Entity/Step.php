<?php

namespace App\Entity;

use App\Repository\RecipeRepository;

class Step
{
    private $steps;

    public function __construct(RecipeRepository $recipeRepository)
    {
        $stepsJson = $recipeRepository->getSteps();

        $this->steps = json_decode($stepsJson);
    }

    public function getSteps()
    {
        return $this->steps;
    }
}