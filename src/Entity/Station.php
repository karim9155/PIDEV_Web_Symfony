<?php

namespace App\Entity;

use App\Repository\StationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Commune;


#[ORM\Entity(repositoryClass: StationRepository::class)]
class Station
{
    #[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: 'integer')]
private ?int $id = null;


    #[ORM\Column(length: 20, nullable: true)]
    #[Groups("starion")]
    private ?string $long_alt = null;

    #[ORM\OneToMany(mappedBy: 'station', targetEntity: MoyenTransport::class)]
    #[Groups("starion")]
    private Collection $id_moy;

      /**
     * @ORM\ManyToOne(targetEntity=Commune::class, inversedBy="stations")
     * @ORM\JoinColumn(name="commune_id", referencedColumnName="id")
     */
    private $commune;


    public function __construct()
    {
        $this->id_moy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLongAlt(): ?string
    {
        return $this->long_alt;
    }

    public function setLongAlt(string $long_alt): self
    {
        $this->long_alt = $long_alt;

        return $this;
    }
/*
    
    public function getIdMoy(): Collection
    {
        return $this->id_moy;
    }

    public function addIdMoy(MoyenTransport $idMoy): self
    {
        if (!$this->id_moy->contains($idMoy)) {
            $this->id_moy->add($idMoy);
            $idMoy->setStation($this);
        }

        return $this;
    }

    public function removeIdMoy(MoyenTransport $idMoy): self
    {
        if ($this->id_moy->removeElement($idMoy)) {
            // set the owning side to null (unless already changed)
            if ($idMoy->getStation() === $this) {
                $idMoy->setStation(null);
            }
        }

        return $this;
    } 
*/
    public function getCommune(): ?Commune
    {
        return $this->commune;
    }

    public function setCommune(?Commune $commune): self
    {
        $this->commune = $commune;

        return $this;
    }
}
