<?php

namespace App\Entity;

use App\Repository\EntrainementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrainementRepository::class)]
class Entrainement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    /**
     * @var Collection<int, Muscle>
     */
    #[ORM\ManyToMany(targetEntity: Muscle::class, inversedBy: 'entrainements')]
    private Collection $muscle;

    public function __construct()
    {
        $this->muscle = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Muscle>
     */
    public function getMuscle(): Collection
    {
        return $this->muscle;
    }

    public function addMuscle(Muscle $muscle): static
    {
        if (!$this->muscle->contains($muscle)) {
            $this->muscle->add($muscle);
        }

        return $this;
    }

    public function removeMuscle(Muscle $muscle): static
    {
        $this->muscle->removeElement($muscle);

        return $this;
    }
}
