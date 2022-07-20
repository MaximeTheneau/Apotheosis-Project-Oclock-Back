<?php

namespace App\DataFixtures;

use App\Entity\Category as EntityCategory;
use App\Model\Category;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

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


        $manager->flush();
    }
}
