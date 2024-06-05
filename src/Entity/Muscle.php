<?php

namespace App\Entity;

use App\Repository\MuscleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MuscleRepository::class)]
class Muscle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:2,max:255,minMessage:"Le nom du muscle doit dépasser 2 caractères",maxMessage:"Le nom du muscle ne doit pas dépasser 255 caractères")]
    #[Assert\NotBlank(message:"Ce champ ne peut pas être vide")]
    private ?string $name = null;

    /**
     * @var Collection<int, Entrainement>
     */
    #[ORM\ManyToMany(targetEntity: Entrainement::class, mappedBy: 'muscle')]
    private Collection $entrainements;

    #[ORM\Column(length: 255)]
    #[Assert\Image(mimeTypes:['image/png','image/jpeg', 'image/jpg', 'image/gif','image/webp'], mimeTypesMessage:"Vous devez upload un fichier jpg, jpeg, png, gif, webP")]
    #[Assert\File(maxSize:"1024k", maxSizeMessage: "La taille du fichier est trop grande")]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(min:40,minMessage:"La description du muscle doit dépasser 40 caractères")]
    #[Assert\NotBlank(message:"Ce champ ne peut pas être vide")]
    private ?string $description = null;

    public function __construct()
    {
        $this->entrainements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Entrainement>
     */
    public function getEntrainements(): Collection
    {
        return $this->entrainements;
    }

    public function addEntrainement(Entrainement $entrainement): static
    {
        if (!$this->entrainements->contains($entrainement)) {
            $this->entrainements->add($entrainement);
            $entrainement->addMuscle($this);
        }

        return $this;
    }

    public function removeEntrainement(Entrainement $entrainement): static
    {
        if ($this->entrainements->removeElement($entrainement)) {
            $entrainement->removeMuscle($this);
        }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
