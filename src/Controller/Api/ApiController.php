<?php

namespace App\Controller\Api;

use App\Model\JsonMessage;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class ApiController extends AbstractController
{
    /**
     * Method who edits object
     *
     * @param object $dataToUpdate : new data to edit 
     * @param object $objectToUpdate : object we went to update
     */
    public function editData(object $dataToUpdate, object $objectToUpdate, UserPasswordHasherInterface $hasher = null)
    {
        foreach ($dataToUpdate->getProps() as $name => $data) {
            if ($name !== 'id') {
                if ($data instanceof ArrayCollection) {
                    if (!$data->isEmpty()) {
                        $regex = "/[s]$/";
                        foreach ($data->getValues() as $value) {
                            $methodName = preg_replace($regex, '', $name);
                            $methodName = 'add' . ucfirst($methodName);

                            $objectToUpdate->$methodName($value);
                        }
                    }
                } elseif (!($data instanceof ArrayCollection) && $data) {
                    $methodName = 'set' . ucfirst($name);
                    if($name === 'password'){
                        $data = $hasher->hashPassword($objectToUpdate, $data);
                    }
                    $objectToUpdate->$methodName($data);
                }
            }
        }

        $objectToUpdate->setUpdatedAt(new DateTime());
    }
    
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
                ],
                'circular_reference_limit' => 2,
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
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
