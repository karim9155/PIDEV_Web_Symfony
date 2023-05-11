<?php

namespace App\Entity;

use App\Repository\LigneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneRepository::class)]
class Ligne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("ligne")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"nom ligne is required")]
    #[Groups("ligne")]
    private ?string $nom_ligne = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"type ligne is required")]
    #[Groups("ligne")]

    private ?string $type_ligne = null;

    #[ORM\OneToMany(mappedBy: 'id_ligne', targetEntity: MoyenTransport::class)]
    #[Groups("ligne")]

    private Collection $id_moy;

    public function __construct()
    {
        $this->id_moy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomLigne(): ?string
    {
        return $this->nom_ligne;
    }

    public function setNomLigne(string $nom_ligne): self
    {
        $this->nom_ligne = $nom_ligne;

        return $this;
    }

    public function getTypeLigne(): ?string
    {
        return $this->type_ligne;
    }

    public function setTypeLigne(string $type_ligne): self
    {
        $this->type_ligne = $type_ligne;

        return $this;
    }

    /**
     * @return Collection<int, MoyenTransport>
     */
    public function getIdMoy(): Collection
    {
        return $this->id_moy;
    }

    public function addIdMoy(MoyenTransport $idMoy): self
    {
        if (!$this->id_moy->contains($idMoy)) {
            $this->id_moy->add($idMoy);
            $idMoy->setIdLigne($this);
        }

        return $this;
    }

    public function removeIdMoy(MoyenTransport $idMoy): self
    {
        if ($this->id_moy->removeElement($idMoy)) {
            // set the owning side to null (unless already changed)
            if ($idMoy->getIdLigne() === $this) {
                $idMoy->setIdLigne(null);
            }
        }

        return $this;
    }
}
