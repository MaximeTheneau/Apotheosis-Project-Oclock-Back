<?php

namespace App\Service;

use App\Controller\Api\ApiController;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use Doctrine\Common\Collections\Collection;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class RecipeService
{
    private $params;
    private $projectDir;
    private $sourcesDir;
    private $recipesImageDir;
    private $apiController;
    private $serializer;

    public function __construct(ContainerBagInterface $params, ApiController $apiController, SerializerInterface $serializer)
    {
        $this->params = $params;
        $this->projectDir = $this->params->get('app.projectDir');
        $this->sourcesDir = $this->params->get('app.sourcesDir');
        $this->recipesImageDir = $this->params->get('app.recipesImageDir');
        $this->apiController = $apiController;
        $this->serializer = $serializer;
    }

    public function setPicture(Recipe $recipe, Request $request, ?File $file)
    {
        $urlPicture = $request->getSchemeAndHttpHost().'/omiam/current/public/sources/images/recipe/';

        if (!$file) {
            $this->deletePicture($recipe);
            switch ($recipe->getCategory()->getId()) {
                case '1':
                    $urlPicture .= 'defaults/drink.png';
                    break;
                
                case '2':
                    $urlPicture .= 'defaults/entre.png';
                    break;
            
                case '3':
                    $urlPicture .= 'defaults/dish.png';
                    break;
        
                case '4':
                    $urlPicture .= 'defaults/cake.png';
                    break;
            }
        } else {
            $urlPicture .= 'recipe_'.$recipe->getId().'.png';

            $file->move($this->projectDir . $this->sourcesDir . $this->recipesImageDir, 'recipe_'.$recipe->getId().'.png');
            // $file->move('/var/www/html/projet-11-omiam-back/public/sources/images/recipe/', 'recipe_'.$recipe->getId().'.png'); //for dev in localhost
        }

        $recipe->setPicture($urlPicture);
    }

    public function deletePicture(Recipe $recipe)
    {
        $filesystem = new Filesystem();

        $pictureDir = $this->projectDir . $this->sourcesDir . $this->recipesImageDir . 'recipe_'.$recipe->getId().'.png';

        if ($filesystem->exists($pictureDir)) {
            $filesystem->remove($pictureDir);
        }
    }

    public function setEntity(array $recipes)
    {
        $this->countNbMiams($recipes);
        $this->usersIdInFavorites($recipes);
    }

    public function countNbMiams(array $recipes)
    {
        foreach ($recipes as $recipe) {
            $recipe->setNbMiams(count($recipe->getUsersWhoFavorized()));
        }
    }

    public function sortRecipesNbMiams(array $recipes)
    {
        $recipesSorted = [];
        foreach ($recipes as $key => $recipe) {
            $recipesSorted[$key] = $recipe->getNbMiams();
        }
        arsort($recipesSorted);


        foreach ($recipesSorted as $index => $recipeSort) {
            $recipesSorted[$index] = $recipes[$index];
        }

        return array_slice($recipesSorted, 0, 3);
    }

    public function usersIdInFavorites(array $recipes)
    {
        foreach ($recipes as $recipe) {
            $users = $recipe->getUsersWhoFavorized()->toArray();
            
            foreach ($users as $user) {
                if (!in_array($user->getId(), $recipe->getUsersId())) {
                    $recipe->addUsersId($user->getId());
                }
            }
        }
    }

    public function setRecipeIngredients($jsonContent, Recipe $recipe)
    {

        $ingredientsJson = (array) json_decode($jsonContent);

        $recipesIngredients = [];

        $regex = "/(\D+)(\d+)$/";
        foreach ($ingredientsJson as $index => $value) {

            foreach ($value as $key => $unit) {
                
                preg_match($regex, $key, $matches);
                if(str_contains($key, 'quantity') || str_contains($key, 'ingredient')){
                    $recipesIngredients[$matches[2]][$matches[1]] = intval($unit);
                } else {
                    $recipesIngredients[$matches[2]][$matches[1]] = $unit;
                }
                
            }  
        }

        foreach ($recipesIngredients as $i => $recipeIngredient) {

            try {
                $recipesIngredients[$i] = $this->serializer->deserialize(json_encode($recipeIngredient), RecipeIngredient::class, 'json');
            } catch (Exception $e) {
                return $this->apiController->json400();
            }

            $recipe->addRecipeIngredient($recipesIngredients[$i]);
 
        }
    }
}
