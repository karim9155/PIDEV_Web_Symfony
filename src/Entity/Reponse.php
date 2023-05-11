<?php

namespace App\Entity;

use ORM\Table;
use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReponseRepository;
use Symfony\Component\Serializer\Annotation\Groups;



#[ORM\Entity(repositoryClass: ReponseRepository::class)]


class Reponse
{
   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("Reponse")]
    private ?int $id_reponse = null;
    
  

    #[ORM\Column(length: 250)]
    #[Groups("Reponse")]
    private ?string $text_rep = null;


    
    #[ORM\OneToOne(targetEntity: Reclamation::class, inversedBy: 'reponse')]
    #[ORM\JoinColumn(name: "id_reclamation", referencedColumnName: "id_reclamation")]
    protected $reclamation;

    public function getIdReponse(): ?string
    {
        return $this->id_reponse;
    }

    public function getId_Reponse(): ?string
    {
        return $this->id_reponse;
    }

    public function getTextRep(): ?string
    {
        return $this->text_rep;
    }

    public function setTextRep(string $text_rep): self
    {
        $this->text_rep = $text_rep;

        return $this;
    }

    public function getText_Rep(): ?string
    {
        return $this->text_rep;
    }

    public function setText_Rep(string $text_rep): self
    {
        $this->text_rep = $text_rep;

        return $this;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): self
    {
        $this->reclamation = $reclamation;

        return $this;
    }

     }