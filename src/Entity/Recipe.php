<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Serializer\Annotation\MaxDepth;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 */
class Recipe
{
    use MainEntity;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups("api_recipes_browse")
     * @Groups("api_recipes_read")
     * @Groups("api_users_read")
     * @Groups("api_users_read_self")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(
     *      message = "Le champ Titre ne doit pas être vide"
     * )
     * 
     * @Assert\Length(
     *      min = 2,
     *      max = 32,
     *      minMessage = "Le Titre doit comporter au minimum {{ limit }} caractères",
     *      maxMessage = "Le Titre doit comporter au maximum {{ limit }} caractères"
     * )
     * 
     * @Groups("api_recipes_browse")
     * @Groups("api_recipes_read")
     * @Groups("api_users_read")
     * @Groups("api_users_read_self")
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("api_recipes_browse")
     * @Groups("api_recipes_read")
     * @Groups("api_users_read")
     * @Groups("api_users_read_self")
     */
    private $caption;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("api_recipes_browse")
     * @Groups("api_recipes_read")
     * @Groups("api_users_read")
     * @Groups("api_users_read_self")
     */
    private $slug;

    /**
     * @ORM\Column(type="json")
     * @Groups("api_recipes_read")
     */
    private $steps = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups("api_recipes_browse")
     * @Groups("api_recipes_read")
     * @Groups("api_users_read")
     * @Groups("api_users_read_self")
     */
    private $picture;

    /**
     * 
     * 
     * @Groups("api_recipes_browse")
     * @Groups("api_recipes_read")
     * @Groups("api_users_read")
     * @Groups("api_users_read_self")
     */
    private $nbMiams;

    /**
     * 
     * 
     * @Groups("api_recipes_browse")
     * @Groups("api_recipes_read")
     * @Groups("api_users_read")
     * @Groups("api_users_read_self")
     */
    private $usersId = [];

    /**
     * @ORM\Column(type="smallint", options={"unsigned":true})
     * 
     * @Groups("api_recipes_read")
     */
    private $duration;

    /**
     * @ORM\Column(type="smallint", options={"unsigned":true})
     * 
     * @Groups("api_recipes_read")
     * @Groups("api_users_read")
     * @Groups("api_users_read_self")
     * @Groups("api_recipes_browse")
     */
    private $difficulty;

    /**
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="recipes")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups("api_recipes_browse")
     * @Groups("api_recipes_read")
     * @Groups("api_users_read")
     * @Groups("api_users_read_self")
     * @Groups("api_recipes_browse")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recipes,")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @MaxDepth(1)
     * 
     * @Groups("api_recipes_browse")
     * @Groups("api_recipes_read")
     * @Groups("api_users_read")
     * @Groups("api_users_read_self")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="favorites")
     */
    private $usersWhoFavorized;

    /**
     * @ORM\OneToMany(targetEntity=RecipeIngredient::class, mappedBy="recipe", orphanRemoval=true, cascade={"persist"})
     * 
     * @Groups("api_recipes_read")
     */
    private $recipeIngredients;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="recipe", orphanRemoval=true, cascade={"persist"})
     * 
     * @Groups("api_recipes_read")
     */
    private $comments;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->recipeIngredients = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->usersWhoFavorized = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSteps(): ?array
    {
        return $this->steps;
    }

    public function setSteps(array $steps): self
    {
        $this->steps = $steps;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getNbMiams(): ?int
    {
        return $this->nbMiams;
    }

    public function setNbMiams(?int $nbMiams): self
    {
        $this->nbMiams = $nbMiams;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->usersWhoFavorized;
    }

    public function addUser(User $user): self
    {
        if (!$this->usersWhoFavorized->contains($user)) {
            $this->usersWhoFavorized[] = $user;
            $user->addFavorite($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->usersWhoFavorized->removeElement($user)) {
            $user->removeFavorite($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients[] = $recipeIngredient;
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setRecipe($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRecipe() === $this) {
                $comment->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersWhoFavorized(): Collection
    {
        return $this->usersWhoFavorized;
    }

    public function addUsersWhoFavorized(User $usersWhoFavorized): self
    {
        if (!$this->usersWhoFavorized->contains($usersWhoFavorized)) {
            $this->usersWhoFavorized[] = $usersWhoFavorized;
            $usersWhoFavorized->addFavorite($this);
        }

        return $this;
    }

    public function removeUsersWhoFavorized(User $usersWhoFavorized): self
    {
        if ($this->usersWhoFavorized->removeElement($usersWhoFavorized)) {
            $usersWhoFavorized->removeFavorite($this);
        }

        return $this;
    }

    /**
     * Get the value of usersId
     */ 
    public function getUsersId()
    {
        return $this->usersId;
    }

    /**
     * Set the value of usersId
     *
     * @return  self
     */ 
    public function addUsersId($usersId)
    {
        $this->usersId[] = $usersId;

        return $this;
    }
}
