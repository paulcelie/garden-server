<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoutineActionRepository")
 */
class RoutineAction implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="routineActions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $groupId;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Routine", inversedBy="routineActions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Routine;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupId(): ?Group
    {
        return $this->groupId;
    }

    public function setGroupId(?Group $groupId): self
    {
        $this->groupId = $groupId;

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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getRoutine(): ?Routine
    {
        return $this->Routine;
    }

    public function setRoutine(?Routine $Routine): self
    {
        $this->Routine = $Routine;

        return $this;
    }
}
