<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("tickets")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "prix is required")]
    #[Assert\Regex(pattern: '/^\d+(\.\d{1,2})?$/', message: 'Invalid price format.')]
    #[Assert\Positive(message: 'Price must be a positive number.')]
    #[Groups("tickets")]
    private ?string $prix = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "status is required")]
    #[Groups("tickets")]
    private ?string $status = null;

    #[ORM\OneToOne(inversedBy: 'id_ticket', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reservation $id_reservation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Type Ticket is required")]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z ]{1,30}$/',
        message: "Type Ticket should only contain letters and spaces, and its length should be between 1 and 30 characters"
    )]
    #[Assert\Type(
        type: 'string',
        message: 'description doit être une chaine de caractére.',
    )]
    #[Groups("tickets")]
    private ?string $nom_ticket = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getIdReservation(): ?Reservation
    {
        return $this->id_reservation;
    }

    public function setIdReservation(Reservation $id_reservation): self
    {
        $this->id_reservation = $id_reservation;

        return $this;
    }

    public function getNomTicket(): ?string
    {
        return $this->nom_ticket;
    }

    public function setNomTicket(?string $nom_ticket): self
    {
        $this->nom_ticket = $nom_ticket;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->id_reservation;
    }
}
