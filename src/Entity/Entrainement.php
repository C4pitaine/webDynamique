<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EntrainementRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EntrainementRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Entrainement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:2,max:255,minMessage:"Le titre de l'entrainement doit dépasser 2 caractères",maxMessage:"Le titre de l'entrainement ne doit pas dépasser 255 caractères")]
    #[Assert\NotBlank(message:"Ce champ ne peut pas être vide")]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    #[Assert\Image(mimeTypes:['image/png','image/jpeg', 'image/jpg', 'image/gif','image/webp'], mimeTypesMessage:"Vous devez upload un fichier jpg, jpeg, png, gif, webP")]
    #[Assert\File(maxSize:"1024k", maxSizeMessage: "La taille du fichier est trop grande")]
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

    /**
     * Permet d'initialiser le slug automatiquement
     *
     * @return void
     */
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function initializeSlug(): void
    {
        if(empty($this->slug))
        {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
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
