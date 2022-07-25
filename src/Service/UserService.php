<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class UserService
{

    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

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

    
    public function setPicture(User $user, Request $request){

        $urlPicture = $request->getSchemeAndHttpHost().'/sources/images/user/';

        if(!$request->files->get('picture')){

            $urlPicture .= 'default/user.png';
        }else {
            $urlPicture .= 'avatar_'.$user->getId().'.png';

            $file = $request->files->get('picture');

            $file->move($this->kernel->getProjectDir().'/public/sources/images/user/', 'avatar_'.$user->getId().'.png');
        }

        $user->setAvatar($urlPicture);
    }
    
}
