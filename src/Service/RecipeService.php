<?php

namespace App\Service;

use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class RecipeService
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function setPicture(Recipe $recipe, Request $request){

        $urlPicture = $request->getSchemeAndHttpHost().'/omiam/current/public/sources/images/recipe/';

        if(!$request->files->get('picture')){

            
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

            $file = $request->files->get('picture');

            $file->move($this->kernel->getProjectDir().'/omiam/current/public/sources/images/recipe/', 'recipe_'.$recipe->getId().'.png');
        }

        $recipe->setPicture($urlPicture);
    }
}
