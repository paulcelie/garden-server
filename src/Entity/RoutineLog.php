<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoutineLogRepository")
 */
class RoutineLog
{
    const STATUS_PENDING = 0;
    const STATUS_STARTED = 1;
    const STATUS_STOPPED = 2;
    const STATUS_SKIPPED = 4;

    const HANDLING_START = 0;
    const HANDLING_STOP  = 1;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Routine", inversedBy="routineLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $routine;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RoutineAction")
     * @ORM\JoinColumn(nullable=false)
     */
    private $action;

    /**
     * @ORM\Column(type="integer")
     */
    private $handling;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $scheduledAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $message;

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

    public function getAction(): ?RoutineAction
    {
        return $this->action;
    }

    public function setAction(?RoutineAction $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getScheduledAt(): ?\DateTimeInterface
    {
        return $this->scheduledAt;
    }

    public function setScheduledAt(\DateTimeInterface $scheduledAt): self
    {
        $this->scheduledAt = $scheduledAt;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeInterface $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getHandling(): ?int
    {
        return $this->handling;
    }

    public function setHandling(int $handling): self
    {
        $this->handling = $handling;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
