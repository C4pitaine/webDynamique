<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:2,max:50,minMessage:"Le nom de la séance doit dépasser 2 caractères",maxMessage:"Le nom de la séance ne doit pas dépasser 50 caractères")]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    /**
     * @var Collection<int, ExosMusculation>
     */
    #[ORM\OneToMany(targetEntity: ExosMusculation::class, mappedBy: 'seance', orphanRemoval: true)]
    private Collection $exosMusculations;

    /**
     * @var Collection<int, ExosCardio>
     */
    #[ORM\OneToMany(targetEntity: ExosCardio::class, mappedBy: 'seances', orphanRemoval: true)]
    private Collection $exosCardios;

    public function __construct()
    {
        $this->exosMusculations = new ArrayCollection();
        $this->exosCardios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, ExosMusculation>
     */
    public function getExosMusculations(): Collection
    {
        return $this->exosMusculations;
    }

    public function addExosMusculation(ExosMusculation $exosMusculation): static
    {
        if (!$this->exosMusculations->contains($exosMusculation)) {
            $this->exosMusculations->add($exosMusculation);
            $exosMusculation->setSeance($this);
        }

        return $this;
    }

    public function removeExosMusculation(ExosMusculation $exosMusculation): static
    {
        if ($this->exosMusculations->removeElement($exosMusculation)) {
            // set the owning side to null (unless already changed)
            if ($exosMusculation->getSeance() === $this) {
                $exosMusculation->setSeance(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ExosCardio>
     */
    public function getExosCardios(): Collection
    {
        return $this->exosCardios;
    }

    public function addExosCardio(ExosCardio $exosCardio): static
    {
        if (!$this->exosCardios->contains($exosCardio)) {
            $this->exosCardios->add($exosCardio);
            $exosCardio->setSeances($this);
        }

        return $this;
    }

    public function removeExosCardio(ExosCardio $exosCardio): static
    {
        if ($this->exosCardios->removeElement($exosCardio)) {
            // set the owning side to null (unless already changed)
            if ($exosCardio->getSeances() === $this) {
                $exosCardio->setSeances(null);
            }
        }

        return $this;
    }
}
