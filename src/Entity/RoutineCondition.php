<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoutineConditionRepository")
 */
class RoutineCondition implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Routine", inversedBy="routineConditions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $routine;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $milimeter;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoutine(): ?Routine
    {
        return $this->routine;
    }

    public function setRoutine(?Routine $routine): self
    {
        $this->routine = $routine;

        return $this;
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

    public function getMilimeter(): ?int
    {
        return $this->milimeter;
    }

    public function setMilimeter(int $milimeter): self
    {
        $this->milimeter = $milimeter;

        return $this;
    }
}
