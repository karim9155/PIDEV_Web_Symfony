<?php

namespace App\Entity;

use App\Repository\CommuneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Station;


#[ORM\Entity(repositoryClass: CommuneRepository::class)]
class Commune
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_commune = null;

    #[ORM\Column(length: 255)]
    private ?string $long_alt = null;

 /**
     * @ORM\OneToMany(targetEntity=Station::class, mappedBy="commune")
     */
    private $stations;

  
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCommune(): ?string
    {
        return $this->nom_commune;
    }

    public function setNomCommune(string $nom_commune): self
    {
        $this->nom_commune = $nom_commune;

        return $this;
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

    

    public function __construct()
    {
        $this->stations = new ArrayCollection();
    }

    /**
     * @return Collection|Station[]
     */
    public function getStations(): Collection
    {
        return $this->stations;
    }

    public function addStation(Station $station): self
    {
        if (!$this->stations->contains($station)) {
            $this->stations[] = $station;
            $station->setCommune($this);
        }

        return $this;
    }

    public function removeStation(Station $station): self
    {
        if ($this->stations->removeElement($station)) {
            // set the owning side to null (unless already changed)
            if ($station->getCommune() === $this) {
                $station->setCommune(null);
            }
        }

        return $this;
    }


   
}
