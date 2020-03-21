<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropositionRepository")
 */
class Proposition
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\survey", inversedBy="propositions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $survey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wording;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Answer", mappedBy="propositions")
     */
    private $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurvey(): ?survey
    {
        return $this->survey;
    }

    public function setSurvey(?survey $survey): self
    {
        $this->survey = $survey;

        return $this;
    }

    public function getWording(): ?string
    {
        return $this->wording;
    }

    public function setWording(string $wording): self
    {
        $this->wording = $wording;

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->addProposition($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            $answer->removeProposition($this);
        }

        return $this;
    }
}
