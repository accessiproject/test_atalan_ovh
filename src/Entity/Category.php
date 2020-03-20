<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     */
    protected $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AssistiveTechnology", mappedBy="category", cascade={"persist"}, orphanRemoval=true)
     */
    protected $assistiveTechnologies;

    public function __construct()
    {
        $this->assistiveTechnologies = new ArrayCollection();
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
     * @return Collection|AssistiveTechnology[]
     */
    public function getAssistiveTechnologies(): Collection
    {
        return $this->assistiveTechnologies;
    }

    public function addAssistiveTechnology(AssistiveTechnology $assistiveTechnology): self
    {
        if (!$this->assistiveTechnologies->contains($assistiveTechnology)) {
            $this->assistiveTechnologies[] = $assistiveTechnology;
            $assistiveTechnology->setCategory($this);
            $this->assistiveTechnologies->add($assistiveTechnology);
        }

        return $this;
    }

    public function removeAssistiveTechnology(AssistiveTechnology $assistiveTechnology): self
    {
        if ($this->assistiveTechnologies->contains($assistiveTechnology)) {
            $this->assistiveTechnologies->removeElement($assistiveTechnology);
            // set the owning side to null (unless already changed)
            if ($assistiveTechnology->getCategory() === $this) {
                $assistiveTechnology->setCategory(null);
            }
        }

        return $this;
    }
}
