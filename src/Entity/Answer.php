<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 */
class Answer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Survey", inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $survey;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $user_agent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $device_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $device_identifier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $device_manufacturer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $device_model;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $os_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $os_version;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $browser_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $browser_version;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email(
     *     message = "L'adresse email {{ value }} est invalide."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $accept;

    /**
     * @ORM\Column(type="datetime")
     */
    private $acceptedat;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdat;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Proposition", inversedBy="answers")
     */
    protected $propositions;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Assistive", inversedBy="answers")
     */
    private $assistives;

    public function __construct()
    {
        $this->propositions = new ArrayCollection();
        $this->assistives = new ArrayCollection();
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

    public function getUserAgent(): ?string
    {
        return $this->user_agent;
    }

    public function setUserAgent(string $user_agent): self
    {
        $this->user_agent = $user_agent;

        return $this;
    }

    public function getDeviceType(): ?string
    {
        return $this->device_type;
    }

    public function setDeviceType(?string $device_type): self
    {
        $this->device_type = $device_type;

        return $this;
    }

    public function getDeviceIdentifier(): ?string
    {
        return $this->device_identifier;
    }

    public function setDeviceIdentifier(?string $device_identifier): self
    {
        $this->device_identifier = $device_identifier;

        return $this;
    }

    public function getDeviceManufacturer(): ?string
    {
        return $this->device_manufacturer;
    }

    public function setDeviceManufacturer(?string $device_manufacturer): self
    {
        $this->device_manufacturer = $device_manufacturer;

        return $this;
    }

    public function getDeviceModel(): ?string
    {
        return $this->device_model;
    }

    public function setDeviceModel(?string $device_model): self
    {
        $this->device_model = $device_model;

        return $this;
    }

    public function getOsName(): ?string
    {
        return $this->os_name;
    }

    public function setOsName(?string $os_name): self
    {
        $this->os_name = $os_name;

        return $this;
    }

    public function getOsVersion(): ?string
    {
        return $this->os_version;
    }

    public function setOsVersion(?string $os_version): self
    {
        $this->os_version = $os_version;

        return $this;
    }

    public function getBrowserName(): ?string
    {
        return $this->browser_name;
    }

    public function setBrowserName(?string $browser_name): self
    {
        $this->browser_name = $browser_name;

        return $this;
    }

    public function getBrowserVersion(): ?string
    {
        return $this->browser_version;
    }

    public function setBrowserVersion(?string $browser_version): self
    {
        $this->browser_version = $browser_version;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAccept(): ?bool
    {
        return $this->accept;
    }

    public function setAccept(?bool $accept): self
    {
        $this->accept = $accept;

        return $this;
    }

    public function getAcceptedat(): ?\DateTimeInterface
    {
        return $this->acceptedat;
    }

    public function setAcceptedat(\DateTimeInterface $acceptedat): self
    {
        $this->acceptedat = $acceptedat;

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

    /**
     * @return Collection|proposition[]
     */
    public function getPropositions(): Collection
    {
        return $this->propositions;
    }

    public function setPropositions($data)
    {
        if (is_array($data)) {
            $this->propositions=$data;
        } else {
            $this->propositions->clear();
            $this->propositions->add($data);
        }
    }
    
    public function addProposition(proposition $proposition): self
    {
        if (!$this->propositions->contains($proposition)) {
            $this->propositions[] = $proposition;
        }

        return $this;
    }

    public function removeProposition(proposition $proposition): self
    {
        if ($this->propositions->contains($proposition)) {
            $this->propositions->removeElement($proposition);
        }

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
        }

        return $this;
    }

    public function removeAssistive(Assistive $assistive): self
    {
        if ($this->assistives->contains($assistive)) {
            $this->assistives->removeElement($assistive);
        }

        return $this;
    }
}
