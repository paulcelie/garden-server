<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BuienRadarRepository")
 */
class BuienRadar implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $location;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?int
    {
        return $this->location;
    }

    public function setLocation(int $location): self
    {
        $this->location = $location;

        return $this;
    }
}
