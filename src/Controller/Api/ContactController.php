<?php

namespace App\Controller\Api;

use App\Repository\ContactRepository;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
/**
 * @Route("/api/contact")
 */
class ContactController extends ApiController
{    
    private $tokenService;
    private $serializer;

    public function __construct(
        TokenStorageInterface $token,
        SerializerInterface $serializer,
    ) {
        $this->tokenService = $token;
        $this->serializer = $serializer;
    }
	
        /**
         * @Route("", name="add_contact", methods={"POST"})
         */
        public function add(Request $request, ContactRepository $contactRepository, MailerInterface $mailer): JsonResponse
        {

        $content = $request->getContent();
        
        try {
            $newContact = $this->serializer->deserialize($content, Contact::class, 'json');
        }catch (Exception $e) {
            return $this->json400();
        }
        
        $email = (new Email())
            ->to('max.the.guymauve@gmail.com')
            ->from($newContact->getEmail())
            ->subject($newContact->getTopic())
            ->text($newContact->getMessage())
            ->html('<p> Name :'.$newContact->getName().' email :'.$newContact->getEmail().'</p><p>'.$newContact->getMessage().'</p>');
        
        $mailer->send($email);

            return $this->json201($newContact, "api_contact_add");
        }
    
	
}