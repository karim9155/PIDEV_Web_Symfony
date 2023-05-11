<?php

namespace App\Entity;

use App\Repository\TrajetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: TrajetRepository::class)]
class Trajet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "integer", options: ["unsigned" => true])]
    #[Assert\NotBlank(message:"Temps parcours est obligatoire")]
    #[Assert\Length(max:2,maxMessage:"Temps parcours ne doit pas depasser 2 chiffres")]
    private ?string $temps_parcours = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Point de depart est obligatoire")]
    #[Assert\Length(max:10,maxMessage:"Point de depart ne doit pas depasser 10 characters")]
    #[Assert\Regex(pattern: '/^[a-zA-Z]*$/', message: 'Point de depart ne doit pas contenir des chiffres')]
    
    private ?string $pts_depart = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Point d'arrive est obligatoire")]
    #[Assert\Regex(pattern: '/^[a-zA-Z]*$/', message: 'Point darrive ne doit pas contenir des chiffres')]
    #[Assert\Length(max:10,maxMessage:"Point d'arrive' ne doit pas depasser 10 characters")]
    private ?string $pts_arrive = null;

    #[ORM\ManyToOne(inversedBy: 'id_trajet')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Iteneraire $id_it = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTempsParcours(): ?string
    {
        return $this->temps_parcours;
    }

    public function setTempsParcours(string $temps_parcours): self
    {
        $this->temps_parcours = $temps_parcours;

        return $this;
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

    public function getIdIt(): ?Iteneraire
    {
        return $this->id_it;
    }

    public function setIdIt(?Iteneraire $id_it): self
    {
        $this->id_it = $id_it;

        return $this;
    }
}
