<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class UserService
{
    private $params;
    private $projectDir;
    private $sourcesDir;
    private $usersImageDir;

    public function __construct(ContainerBagInterface $params)
    {
        $this->params = $params;
        $this->projectDir = $this->params->get('app.projectDir');
        $this->sourcesDir = $this->params->get('app.sourcesDir');
        $this->usersImageDir = $this->params->get('app.usersImageDir');
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

    
    public function setPicture(User $user, Request $request, ?File $file)
    {
        $urlPicture = $request->getSchemeAndHttpHost().'/omiam/sources/images/user/';

        if (!$file) {
            $this->deletePicture($user);
            $urlPicture .= 'default/user.jpg';
        } else {
            $urlPicture .= 'avatar_'.$user->getId().'.jpg';

            // $file->move('/var/www/html/omiam/current/public/sources/images/user/', 'avatar_'.$user->getId().'.jpg');
            $file->move($this->projectDir . $this->sourcesDir . $this->usersImageDir, 'avatar_'.$user->getId().'.jpg');
        }

        $user->setAvatar($urlPicture);
    }

    public function deletePicture(User $user)
    {
        $filesystem = new Filesystem();

        $pictureDir = $this->projectDir . $this->sourcesDir . $this->usersImageDir . 'avatar_'.$user->getId().'.jpg';

        if ($filesystem->exists($pictureDir)) {
            $filesystem->remove($pictureDir);
        }
    }

    /**
     * Function Who delete superfluous data in favorites->user
     *
     */
    public function selfRead($jsonContent){

        foreach ($jsonContent as $key => $value) {
            if($key === "favorites"){
                foreach ($value as $prop) {
                    foreach ($prop as $name => $object) {
                        if($name === "user"){
                            foreach ($object as $index => $array) {
                                if($index === "recipes" || $index === "favorites"){
                                    unset($object->$index);
                                }
                            }
                        }
                    }
                }
            }
        }

    }

    public function selfReadMiamsRecipes($jsonContent){

        foreach ($jsonContent as $prop) {
            foreach ($prop as $name => $object) {
                if($name === "user"){
                    foreach ($object as $index => $array) {
                        if($index === "recipes" || $index === "favorites"){
                            unset($object->$index);
                        }
                    }
                }
            }
        }
        
    }
}
