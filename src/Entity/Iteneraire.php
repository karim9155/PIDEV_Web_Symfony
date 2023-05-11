<?php

namespace App\Entity;

use App\Repository\IteneraireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: IteneraireRepository::class)]
class Iteneraire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("it")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Point de depart est obligatoire")]
    #[Assert\Length(max:10,maxMessage:"Point de depart ne doit pas depasser 10 characters")]
    #[Assert\Regex(pattern: '/^[a-zA-Z]*$/', message: 'Point de depart ne doit pas contenir des chiffres')]
    #[Groups("it")]
    private ?string $pts_depart = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Point d'arrive' est obligatoire")]
    #[Assert\Length(max:10,maxMessage:"Point d'arrive' ne doit pas depasser 10 characters")]
    #[Assert\Regex(pattern: '/^[a-zA-Z]*$/', message: 'Point darrive ne doit pas contenir des chiffres')]
    #[Groups("it")]
    private ?string $pts_arrive = null;

    #[ORM\OneToMany(mappedBy: 'id_it', targetEntity: Trajet::class)]
    
    private Collection $id_trajet;
    
    #[ORM\OneToMany(mappedBy: 'id_it', targetEntity: Reservation::class)]
    
    private Collection $id_reservation;
    

    public function __construct()
    {
        $this->id_trajet = new ArrayCollection();
        $this->id_reservation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPtsDepart(): ?string
    {
        return $this->pts_depart;
    }

    public function setPtsDepart(string $pts_depart): self
    {
        $this->pts_depart = $pts_depart;

        return $this;
    }

    public function getPtsArrive(): ?string
    {
        return $this->pts_arrive;
    }

    public function setPtsArrive(string $pts_arrive): self
    {
        $this->pts_arrive = $pts_arrive;

        return $this;
    }

    /**
     * @return Collection<int, Trajet>
     */
    public function getIdTrajet(): Collection
    {
        return $this->id_trajet;
    }

    public function addIdTrajet(Trajet $idTrajet): self
    {
        if (!$this->id_trajet->contains($idTrajet)) {
            $this->id_trajet->add($idTrajet);
            $idTrajet->setIdIt($this);
        }

        return $this;
    }

    public function removeIdTrajet(Trajet $idTrajet): self
    {
        if ($this->id_trajet->removeElement($idTrajet)) {
            // set the owning side to null (unless already changed)
            if ($idTrajet->getIdIt() === $this) {
                $idTrajet->setIdIt(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getIdReservation(): Collection
    {
        return $this->id_reservation;
    }

    public function addIdReservation(Reservation $idReservation): self
    {
        if (!$this->id_reservation->contains($idReservation)) {
            $this->id_reservation->add($idReservation);
            $idReservation->setIdIt($this);
        }

        return $this;
    }

    public function removeIdReservation(Reservation $idReservation): self
    {
        if ($this->id_reservation->removeElement($idReservation)) {
            // set the owning side to null (unless already changed)
            if ($idReservation->getIdIt() === $this) {
                $idReservation->setIdIt(null);
            }
        }

        return $this;
    }
}
