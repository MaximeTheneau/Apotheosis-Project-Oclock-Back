<?php

namespace App\Service;

use App\Entity\Recipe;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class RecipeService
{
    private $params;
    private $projectDir;
    private $sourcesDir;
    private $recipesImageDir;

    public function __construct(ContainerBagInterface $params)
    {
        $this->params = $params;
        $this->projectDir = $this->params->get('app.projectDir');
        $this->sourcesDir = $this->params->get('app.sourcesDir');
        $this->usersImageDir = $this->params->get('app.recipesImageDir');
    }

    public function setPicture(Recipe $recipe, Request $request, ?File $file){

        $urlPicture = $request->getSchemeAndHttpHost().'/omiam/current/public/sources/images/recipe/';

        if(!$file){

            
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
        }else {
            $urlPicture .= 'recipe_'.$recipe->getId().'.png';

            $file->move($this->projectDir . $this->sourcesDir . $this->recipesImageDir,  'recipe_'.$recipe->getId().'.png');
            // $file->move('/var/www/html/projet-11-omiam-back/public/sources/images/recipe/', 'recipe_'.$recipe->getId().'.png'); //for dev in localhost
        }

        $recipe->setPicture($urlPicture);
    }
}
