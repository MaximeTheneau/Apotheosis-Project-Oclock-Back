<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)   
 */
class Contact
{
    use MainEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups("api_contact_add")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("api_contact_add")
    * @ORM\JoinColumn(nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("api_contact_add")
     * @ORM\JoinColumn(nullable=false)
     * 
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("api_contact_add")
     * @ORM\JoinColumn(nullable=false)
     */
    private $topic;

    /**
     * @Groups("api_contact_add")
     * @ORM\Column(type="string", length=1024)
     * @ORM\JoinColumn(nullable=false)
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
