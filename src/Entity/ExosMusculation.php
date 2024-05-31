<?php

namespace App\Entity;

use App\Repository\ExosMusculationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExosMusculationRepository::class)]
class ExosMusculation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'exosMusculations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Seance $seance = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:2,max:255,minMessage:"Le nom de l'exercice muscu doit dépasser 2 caractères",maxMessage:"Le nom de l'exercice muscu ne doit pas dépasser 255 caractères")]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\Range(min:1,max:100,notInRangeMessage:"Le nombre de séries doit être entre 1 et 100")]
    private ?int $series = null;

    #[ORM\Column]
    #[Assert\Range(min:1,max:100,notInRangeMessage:"Le nombre de répétitions doit être entre 1 et 100")]
    private ?int $repetitions = null;

    #[ORM\Column]
    #[Assert\Range(min:1,max:1000,notInRangeMessage:"Le poids doit être entre 1 et 1000 KG")]
    private ?int $poids = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeance(): ?Seance
    {
        return $this->seance;
    }

    public function setSeance(?Seance $seance): static
    {
        $this->seance = $seance;

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

    public function getSeries(): ?int
    {
        return $this->series;
    }

    public function setSeries(int $series): static
    {
        $this->series = $series;

        return $this;
    }

    public function getRepetitions(): ?int
    {
        return $this->repetitions;
    }

    public function setRepetitions(int $repetitions): static
    {
        $this->repetitions = $repetitions;

        return $this;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(int $poids): static
    {
        $this->poids = $poids;

        return $this;
    }
}
