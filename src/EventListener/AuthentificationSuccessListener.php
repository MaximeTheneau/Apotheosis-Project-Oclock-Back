<?php

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthentificationSuccessListener{

    public function onAuthentificationSucces(AuthenticationSuccessEvent $event){

        $data = $event->getData();

        /**
         * @var User
         */
        $user = $event->getUser();

        $data["logs"] =  true;
        $data["userid"] =  $user->getId();
        $data["pseudo"] =  $user->getPseudo();
        $data["avatar"] =  $user->getAvatar();
        $data["role"] =   $user->getRoles()[0];


        $event->setData($data);
    }
}