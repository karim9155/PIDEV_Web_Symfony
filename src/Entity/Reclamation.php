<?php

namespace App\Entity;


use ORM\Table;
use App\Entity\Reponse;
use App\Entity\Utilisateur;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReclamationRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]





class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("Reclamation")]
    private ?int $id_reclamation = null;



    #[ORM\Column(length: 250)]
    #[Groups("Reclamation")]
    private ?string $message_rec = null;


    #[ORM\Column(length: 250)]
    #[Groups("Reclamation")]
    private ?string $objet = null;

    #[ORM\Column(length: 250)]
    #[Groups("Reclamation")]
    private ?string $statut = null;

    #[ORM\Column(type: "date")]
    #[Groups("Reclamation")]
    private ?\DateTimeInterface $date_rec = null;


    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "idUser", referencedColumnName: "id")]
    protected $idUser;


#[ORM\OneToOne(targetEntity: Reponse::class, mappedBy: 'reclamation')]
    private $reponse;

public function getIdReclamation(): ?int
{
    return $this->id_reclamation;
}

public function getId_Reclamation(): ?int
{
    return $this->id_reclamation;
}


public function getMessageRec(): ?string
{
    return $this->message_rec;
}

public function setMessageRec(string $message_rec): self
{
    $this->message_rec = $message_rec;

    return $this;
}


public function getMessage_Rec(): ?string
{
    return $this->message_rec;
}

public function setMessage_Rec(string $message_rec): self
{
    $this->message_rec = $message_rec;

    return $this;
}

public function getObjet(): ?string
{
    return $this->objet;
}

public function setObjet(string $objet): self
{
    $this->objet = $objet;

    return $this;
}

public function getStatut(): ?string
{
    return $this->statut;
}

public function setStatut(string $statut): self
{
    $this->statut = $statut;

    return $this;
}

public function getDate_Rec(): ?\DateTimeInterface
{
    return $this->date_rec;
}

public function setDate_Rec(\DateTimeInterface $date_rec): self
{
    $this->date_rec = $date_rec;

    return $this;
}

public function getDateRec(): ?\DateTimeInterface
{
    return $this->date_rec;
}

public function setDateRec(\DateTimeInterface $date_rec): self
{
    $this->date_rec = $date_rec;

    return $this;
}

public function getIdUser(): ?User
{
    return $this->idUser;
}

public function setIdUser(?User $idUser): self
{
    $this->idUser = $idUser;

    return $this;
}

public function getReponse(): ?Reponse
{
    return $this->reponse;
}

public function setReponse(?Reponse $reponse): self
{
    // unset the owning side of the relation if necessary
    if ($reponse === null && $this->reponse !== null) {
        $this->reponse->setReclamation(null);
    }

    // set the owning side of the relation if necessary
    if ($reponse !== null && $reponse->getReclamation() !== $this) {
        $reponse->setReclamation($this);
    }

    $this->reponse = $reponse;

    return $this;
}

}
