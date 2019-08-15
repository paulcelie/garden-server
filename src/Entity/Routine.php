<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoutineRepository")
 */
class Routine implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $days;

    /**
     * @ORM\Column(type="time")
     */
    private $startTime;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RoutineCondition", mappedBy="routine", orphanRemoval=true)
     */
    private $routineConditions;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $conditionType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RoutineAction", mappedBy="Routine", orphanRemoval=true)
     */
    private $routineActions;

    public function __construct()
    {
        $this->routineConditions = new ArrayCollection();
        $this->routineActions = new ArrayCollection();
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

    public function getDays(): ?string
    {
        return $this->days;
    }

    public function setDays(string $days): self
    {
        $this->days = $days;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * @return Collection|RoutineCondition[]
     */
    public function getRoutineConditions(): Collection
    {
        return $this->routineConditions;
    }

    public function addRoutineCondition(RoutineCondition $routineCondition): self
    {
        if (!$this->routineConditions->contains($routineCondition)) {
            $this->routineConditions[] = $routineCondition;
            $routineCondition->setRoutine($this);
        }

        return $this;
    }

    public function removeRoutineCondition(RoutineCondition $routineCondition): self
    {
        if ($this->routineConditions->contains($routineCondition)) {
            $this->routineConditions->removeElement($routineCondition);
            // set the owning side to null (unless already changed)
            if ($routineCondition->getRoutine() === $this) {
                $routineCondition->setRoutine(null);
            }
        }

        return $this;
    }

    public function getConditionType(): ?string
    {
        return $this->conditionType;
    }

    public function setConditionType(?string $conditionType): self
    {
        $this->conditionType = $conditionType;

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
            $routineAction->setRoutine($this);
        }

        return $this;
    }

    public function removeRoutineAction(RoutineAction $routineAction): self
    {
        if ($this->routineActions->contains($routineAction)) {
            $this->routineActions->removeElement($routineAction);
            // set the owning side to null (unless already changed)
            if ($routineAction->getRoutine() === $this) {
                $routineAction->setRoutine(null);
            }
        }

        return $this;
    }

    public function clearRoutineActions() {
       $this->routineActions = new ArrayCollection();
    }

    public function clearRoutineConditions() {
        $this->routineConditions = new ArrayCollection();
    }
}
