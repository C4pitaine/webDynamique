<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    /**
     * @var Collection<int, ExosMusculation>
     */
    #[ORM\OneToMany(targetEntity: ExosMusculation::class, mappedBy: 'seance', orphanRemoval: true)]
    private Collection $exosMusculations;

    public function __construct()
    {
        $this->exosMusculations = new ArrayCollection();
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
}
