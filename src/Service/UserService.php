<?php

namespace App\Service;

class UserService
{
    /**
     * This method makes a sum of nbMiams of all recipe's user
     *
     * @param array $usersBdd Array of User Object
     * @return array $users Array with User Object and nbMiams of user
     */
    public function miamsCalcul(array $usersBdd): array
    {
        $users = [];

        foreach ($usersBdd as $index => $user) {
            $nbMiamsUser = 0;
            
            $users[$index]['user'] = $user;

            foreach ($user->getRecipes() as $recipe) {
                $nbMiamsUser += $recipe->getNbMiams();
            }

            $users[$index]["nbMiamsUser"] = $nbMiamsUser;
            
        }
        return $users;
    }
}
