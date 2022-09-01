<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Groups("api_recipes_browse")
     * @Groups("api_recipes_read")
     * @Groups("api_users_read")
     * @Groups("api_users_read_self")
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Recipe::class, mappedBy="category")
     */
    private $recipes;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Groups("api_recipes_browse")
     * @Groups("api_recipes_read")
     * @Groups("api_users_read")
     * @Groups("api_users_read_self")
     */
    private $iconName;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->setCategory($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getCategory() === $this) {
                $recipe->setCategory(null);
            }
        }

        return $this;
    }

    public function getIconName(): ?string
    {
        return $this->iconName;
    }

    public function setIconName(string $iconName): self
    {
        $this->iconName = $iconName;

        return $this;
    }
}
