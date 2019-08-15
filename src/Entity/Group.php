<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 * @Table(name="garden_group")
 */
class Group implements EntityInterface
{
    const STATE_ON = 'on';
    const STATE_OFF = 'off';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $externalId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sprinkler", mappedBy="groupId", orphanRemoval=true)
     */
    private $sprinklers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RoutineAction", mappedBy="groupId", orphanRemoval=true)
     */
    private $routineActions;

    public function __construct()
    {
        $this->sprinklers = new ArrayCollection();
        $this->routineActions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
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

    /**
     * @return Collection|Sprinkler[]
     */
    public function getSprinklers(): Collection
    {
        return $this->sprinklers;
    }

    public function addSprinkler(Sprinkler $sprinkler): self
    {
        if (!$this->sprinklers->contains($sprinkler)) {
            $this->sprinklers[] = $sprinkler;
            $sprinkler->setGroupId($this);
        }

        return $this;
    }

    public function removeSprinkler(Sprinkler $sprinkler): self
    {
        if ($this->sprinklers->contains($sprinkler)) {
            $this->sprinklers->removeElement($sprinkler);
            // set the owning side to null (unless already changed)
            if ($sprinkler->getGroupId() === $this) {
                $sprinkler->setGroupId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RoutineAction[]
     */
    public function getRoutineActions(): Collection
    {
        return $this->routineActions;
    }

    public function addRoutineAction(RoutineAction $routineAction): self
    {
        if (!$this->routineActions->contains($routineAction)) {
            $this->routineActions[] = $routineAction;
            $routineAction->setGroupId($this);
        }

        return $this;
    }

    public function removeRoutineAction(RoutineAction $routineAction): self
    {
        if ($this->routineActions->contains($routineAction)) {
            $this->routineActions->removeElement($routineAction);
            // set the owning side to null (unless already changed)
            if ($routineAction->getGroupId() === $this) {
                $routineAction->setGroupId(null);
            }
        }

        return $this;
    }
}
