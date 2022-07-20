<?php

namespace App\DataFixtures;

use App\Entity\Category as EntityCategory;
use App\Entity\User;
use App\Model\Category;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;

class AppFixtures extends Fixture
{
    /**
     * Connection Object to connect to DataBase
     *
     * @var Connection
     */
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    private function truncate(){
        
        // We turn off checking foreign key contraint
        $this->connection->executeQuery('SET foreign_key_checks = 0');

        // truncate tables
        $this->connection->executeQuery('TRUNCATE TABLE category');
        $this->connection->executeQuery('TRUNCATE TABLE user');

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
        
        foreach ($categoriesModel->categories as $name) {
            $category = new EntityCategory;

            $category->setName($name);
            $category->setCreatedAt(new DateTime());
            $manager->persist($category);
        }

        //-----------------------------------------------------------------
        //                      Create Users
        //-----------------------------------------------------------------

        $user1 = new User;
        $user1->setPseudo('User1');
        $user1->setEmail(('user1@user.com'));
        $user1->setPassword('$2y$13$6WRvlR2gUMEpi2.VGBvpu.B4QLgmpPfSHqRAHSsJRTB9kkdpoMzY6'); // password : user1
        $user1->setRole('ROLE_USER');
        $user1->setCreatedAt(new DateTime());
        $manager->persist($user1);

        $user2 = new User;
        $user2->setPseudo('User2');
        $user2->setEmail(('user2@user.com'));
        $user2->setPassword('$2y$13$/I815Wmp/zxUVLboMLk.zOZwLJwkOoohP0lSvkga7V/W2APDuHfBa'); // password : user2
        $user2->setRole('ROLE_USER');
        $user2->setCreatedAt(new DateTime());
        $manager->persist($user2);


        $manager->flush();
    }
}
