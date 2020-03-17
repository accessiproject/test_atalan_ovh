<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SurveyRepository")
 */
class Survey
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=300)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=500)
     */
    protected $question;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $multiple;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $closing_message;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdat;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedat;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $closedat;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Proposition", mappedBy="survey", cascade={"persist"}, orphanRemoval=true)
     */
    protected $propositions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TechnicalComponent", mappedBy="survey", cascade={"persist"}, orphanRemoval=true)
     */
    protected $technicalComponents;

    public function __construct()
    {
        $this->propositions = new ArrayCollection();
        $this->technicalComponents = new ArrayCollection();
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

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getMultiple(): ?bool
    {
        return $this->multiple;
    }

    public function setMultiple(bool $multiple): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getClosingMessage(): ?string
    {
        return $this->closing_message;
    }

    public function setClosingMessage(?string $closing_message): self
    {
        $this->closing_message = $closing_message;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(\DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getUpdatedat(): ?\DateTimeInterface
    {
        return $this->updatedat;
    }

    public function setUpdatedat(\DateTimeInterface $updatedat): self
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    public function getClosedat(): ?\DateTimeInterface
    {
        return $this->closedat;
    }

    public function setClosedat(\DateTimeInterface $closedat): self
    {
        $this->closedat = $closedat;

        return $this;
    }

    /**
    * @ORM\PrePersist
    * @ORM\PreUpdate
    */
    public function updatedTimestamps(): void {
        $dateTimeNow = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $this->setUpdatedat($dateTimeNow);
        if ($this->getCreatedat() === null) {
            $this->setCreatedat($dateTimeNow);
        }
    }

    /**
     * @return Collection|Proposition[]
     */
    public function getPropositions(): Collection
    {
        return $this->propositions;
    }

    public function addProposition(Proposition $proposition): self
    {
        if (!$this->propositions->contains($proposition)) {
            $this->propositions[] = $proposition;
            $proposition->setSurvey($this);
            $this->propositions->add($proposition);
        }

        return $this;
    }

    public function removeProposition(Proposition $proposition): self
    {
        if ($this->propositions->contains($proposition)) {
            $this->propositions->removeElement($proposition);
            // set the owning side to null (unless already changed)
            if ($proposition->getSurvey() === $this) {
                $proposition->setSurvey(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TechnicalComponent[]
     */
    public function getTechnicalComponents(): Collection
    {
        return $this->technicalComponents;
    }

    public function addTechnicalComponent(TechnicalComponent $technicalComponent): self
    {
        if (!$this->technicalComponents->contains($technicalComponent)) {
            $this->technicalComponents[] = $technicalComponent;
            $technicalComponent->setSurvey($this);
            $this->technicalComponents->add($technicalComponent);
        }

        return $this;
    }

    public function removeTechnicalComponent(TechnicalComponent $technicalComponent): self
    {
        if ($this->technicalComponents->contains($technicalComponent)) {
            $this->technicalComponents->removeElement($technicalComponent);
            // set the owning side to null (unless already changed)
            if ($technicalComponent->getSurvey() === $this) {
                $technicalComponent->setSurvey(null);
            }
        }

        return $this;
    }
}
