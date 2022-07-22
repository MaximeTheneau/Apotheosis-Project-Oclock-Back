<?php

namespace App\Controller\Api;

use App\Model\JsonMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    public function json404()
    {
        $error = new JsonMessage('Elément non trouvé', 404);
        return $this->json(
            $error
        );
    }

    public function json200($data, string $group)
    {
        return $this->json(
            $data,
            200,
            [],
            [
                "groups"=>
                [
                    $group
                ]
            ]
        );
    }

    public function json201($data, string $group)
    {
        return $this->json(
            $data,
            201,
            [],
            [
                "groups" =>
                [
                    $group
                ]
            ]
        );
    }

    public function json204()
    {
        $message = new JsonMessage('L\'élément a bien été supprimé', 204);

        return $this->json(
            $message
        );
    }

    public function json422($errors, $data, $group)
    {
        $messages = [];

        for ($i=0; $i < count($errors); $i++) {
            $messages['error'.$i] = $errors[$i]->getMessage();
        }

        return $this->json(
            [$data, $messages],
            422,
            [],
            [
                "groups" =>
                [
                    $group
                ]
            ]
        );
    }

    public function json400()
    {
        return $this->json(
            ['error' => 'Le JSON est mal formé !'],
            400
        );
    }

    public function json403()
    {
        $error = new JsonMessage('Vous n\'avez pas les droits', 403);

        return $this->json(
            $error,
            403
        );
    }
}
