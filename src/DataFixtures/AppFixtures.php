<?php

namespace App\DataFixtures;

use DateTime;
use App\Model\Category;


use Doctrine\Persistence\ObjectManager;
use App\Entity\Category as EntityCategory;
use App\Entity\Ingredient as EntityIngredient;
use App\Model\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{


    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $categoriesModel = new Category;
        

        foreach ($categoriesModel->categories as $name) {
            $category = new EntityCategory;

            $category->setName($name);
            $category->setCreatedAt(new DateTime());
            $manager->persist($category);
        }

        
        //---------------------------------------------------------
        //                  Create Ingredients
        //---------------------------------------------------------

        $ingredientsModel = new Ingredient;

        foreach ($ingredientsModel->ingredients as $name) {
            $ingredient = new EntityIngredient;
        
            $ingredient->setName($name);
            $ingredient->setCreatedAt(new DateTime());
            $manager->persist($ingredient);
        }

        $manager->flush();
    }
}
