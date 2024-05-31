<?php

namespace App\Entity;

use App\Repository\ExosCardioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExosCardioRepository::class)]
class ExosCardio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'exosCardios')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Seance $seances = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:2,max:255,minMessage:"Le nom de l'exercice cardio doit dépasser 2 caractères",maxMessage:"Le nom de l'exercice cardio ne doit pas dépasser 255 caractères")]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\Range(min:1,max:1440,notInRangeMessage:"Le temps d'exercice doit être entre 1 et 1440 minutes")]
    private ?float $time = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeances(): ?Seance
    {
        return $this->seances;
    }

    public function setSeances(?Seance $seances): static
    {
        $this->seances = $seances;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTime(): ?float
    {
        return $this->time;
    }

    public function setTime(float $time): static
    {
        $this->time = $time;

        return $this;
    }
}
