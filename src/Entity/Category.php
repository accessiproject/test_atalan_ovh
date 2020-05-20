<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Erreur. Ce champ est obligatoire.") 
     */
    protected $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Assistive", mappedBy="category", cascade={"persist"}, orphanRemoval=true)
     */
    protected $assistives;

    public function __construct()
    {
        $this->assistives = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Assistive[]
     */
    public function getAssistives(): Collection
    {
        return $this->assistives;
    }

    public function addAssistive(Assistive $assistive): self
    {
        if (!$this->assistives->contains($assistive)) {
            $this->assistives[] = $assistive;
            $assistive->setCategory($this);
            $this->assistives->add($assistive);
        }

        return $this;
    }

    public function removeAssistive(Assistive $assistive): self
    {
        if ($this->assistives->contains($assistive)) {
            $this->assistives->removeElement($assistive);
            // set the owning side to null (unless already changed)
            if ($assistive->getCategory() === $this) {
                $assistive->setCategory(null);
            }
        }

        return $this;
    }
}
