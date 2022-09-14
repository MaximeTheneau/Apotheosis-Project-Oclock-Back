<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use App\Model\Category;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category as EntityCategory;
use App\Entity\Comment;
use App\Entity\Ingredient as EntityIngredient;
use App\Entity\Recipe as EntityRecipe;
use App\Entity\RecipeIngredient;
use App\Model\Ingredient;
use App\Model\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    /**
     * Connection Object to connect to DataBase
     *
     * @var Connection
     */
    private Connection $connection;

    private SluggerInterface $slugger;

    /**
     * We need to have KernelInterface to get the Project Dir
     *
     * @var KernelInterface
     */
    private KernelInterface $kernel;

    public function __construct(
        Connection $connection,
        SluggerInterface $slugger,
        KernelInterface $kernel
    )
    {
        $this->connection = $connection;
        $this->slugger = $slugger;
        $this->kernel = $kernel;
    }

    private function truncate()
    {
        
        // We turn off checking foreign key contraint
        $this->connection->executeQuery('SET foreign_key_checks = 0');

        // truncate tables

        $this->connection->executeQuery('TRUNCATE TABLE user_recipe');
        $this->connection->executeQuery('TRUNCATE TABLE category');
        $this->connection->executeQuery('TRUNCATE TABLE user');
        $this->connection->executeQuery('TRUNCATE TABLE ingredient');
        $this->connection->executeQuery('TRUNCATE TABLE recipe');
        $this->connection->executeQuery('TRUNCATE TABLE recipe_ingredient');
        $this->connection->executeQuery('TRUNCATE TABLE comment');
        

        // We turn on checking foreign key contraint
        $this->connection->executeQuery('SET foreign_key_checks = 1');
    }

    public function load(ObjectManager $manager): void
    {
        $this->truncate();

        //-----------------------------------------------------------------
        //                      Create Categories
        //-----------------------------------------------------------------

        $categoriesModel = new Category;
        
        $categories = [];
        foreach ($categoriesModel->categories as $categoryModel) {
            $category = new EntityCategory;

            $category->setName($categoryModel['name']);
            $category->setIconName($categoryModel['icon']);
            $category->setCreatedAt(new DateTime());
            $manager->persist($category);

            $categories[] = $category;
        }


        
        //---------------------------------------------------------
        //                  Create Ingredients
        //---------------------------------------------------------

        $ingredientsModel = new Ingredient;

        $ingredients = [];
        foreach ($ingredientsModel->ingredients as $name) {
            $ingredient = new EntityIngredient;
        
            $ingredient->setName($name);
            $ingredient->setCreatedAt(new DateTime());
            $manager->persist($ingredient);
            $ingredients[] = $ingredient;
        }

        //-----------------------------------------------------------------
        //                      Create Users
        //-----------------------------------------------------------------

        $users = [];

        $user1 = new User;
        $user1->setPseudo('User1');
        $user1->setEmail(('user1@user.com'));
        $user1->setPassword('$2y$13$6WRvlR2gUMEpi2.VGBvpu.B4QLgmpPfSHqRAHSsJRTB9kkdpoMzY6'); // password : user1
        $user1->setRoles(['ROLE_USER']);
        $user1->setCreatedAt(new DateTime());
        $manager->persist($user1);

        $users[] = $user1;

        $user2 = new User;
        $user2->setPseudo('User2');
        $user2->setEmail(('user2@user.com'));
        $user2->setPassword('$2y$13$/I815Wmp/zxUVLboMLk.zOZwLJwkOoohP0lSvkga7V/W2APDuHfBa'); // password : user2
        $user2->setRoles(['ROLE_USER']);
        $user2->setCreatedAt(new DateTime());
        $manager->persist($user2);

        $users[] = $user2;

        $userManager = new User;
        $userManager->setPseudo('Manager');
        $userManager->setEmail(('manager@omiam.com'));
        $userManager->setPassword('$2y$13$R6esCiAvcNKeDIu/spJG0.Yb5mjhN4fzF26gm1ir/LwkaE2J48u8m'); // password : manager
        $userManager->setRoles(['ROLE_MANAGER']);
        $userManager->setCreatedAt(new DateTime());
        $manager->persist($userManager);

        $userAdmin = new User;
        $userAdmin->setPseudo('Admin');
        $userAdmin->setEmail(('admin@omiam.com'));
        $userAdmin->setPassword('$2y$13$unl/SgZ2viDLF5.u1um4GOJLj4nipAAM6TOgYjK1wKp7Ofgk7ptcO'); // password : admin
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setCreatedAt(new DateTime());
        $manager->persist($userAdmin);


        //-----------------------------------------------------------------
        //                      Create Recipes
        //-----------------------------------------------------------------

        $recipesModel = new Recipe();

        $recipes = [];

        // We create Id Recipe to manage easier picture's url
        $recipeId = 1;
        foreach ($recipesModel->recipes as $recipe) {
            $newRecipe = new EntityRecipe();

            $newRecipe->setTitle($recipe['title']);
            $newRecipe->setCaption($recipe['caption'] ?? null);
            $newRecipe->setSlug($this->slugger->slug($recipe['title']));
            $newRecipe->setSteps($recipe['steps']);
            $newRecipe->setDuration($recipe['duration']);
            $newRecipe->setDifficulty($recipe['difficulty']);
            $newRecipe->setCreatedAt(new DateTime());
            $newRecipe->setCategory($categories[$recipe["category"]-1]);
            $newRecipe->setUser($users[rand(0, count($users)-1)]);
            $newRecipe->setNbMiams(rand(0, 15));

            // Get the project directory
            $basePath = $this->kernel->getProjectDir();

            // Generate picture's url to set picture's recipe
            $newRecipe->setPicture("http://localhost/Omiam-Back/public/omiam/sources/images/recipe/recipe_".$recipeId.".jpg");
            $recipeId++;

            foreach ($recipe['recipeIngredients'] as $ingredient) {
                $recipeIngredient = new RecipeIngredient();

                $recipeIngredient->setRecipe($newRecipe);
                $recipeIngredient->setIngredient($ingredients[$ingredient["ingredientId"]-1]);
                $recipeIngredient->setUnit($ingredient["unit"]);
                $recipeIngredient->setQuantity($ingredient["quantity"]);

                $manager->persist($recipeIngredient);
            }

            $manager->persist($newRecipe);

            $recipes[] = $newRecipe;
        }


        //-----------------------------------------------------------------
        //                      Create Comments
        //-----------------------------------------------------------------

        foreach ($recipes as $recipe) {
            $newComment = new Comment();

            $newComment->setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis, cupiditate.');
            $newComment->setUser($users[rand(0, count($users)-1)]);
            $newComment->setRecipe($recipe);
            $newComment->setCreatedAt(new DateTime());

            $manager->persist($newComment);
        }

        $manager->flush();
    }
}
