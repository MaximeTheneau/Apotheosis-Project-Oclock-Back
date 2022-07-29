<?php

namespace App\Controller\Back;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $userRepo;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
    }

    /**
     * @Route("/back/home", name="app_back_home", methods={"GET"})
     */

    public function home(UserRepository $userRepository): Response
    {
        $nbUsers = $this->userRepo->findNbUsers();
        //dd($nbUsers);
        
        return $this->render('back/home.html.twig', [
        'nbUsers' => $nbUsers]);
        
    }
}
