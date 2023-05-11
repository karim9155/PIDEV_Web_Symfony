<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity as ConstraintsUniqueEntity;
use Symfony\Component\Validator\Constraints\Unique;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UnqueEntity;
use Symfony\Component\Validator\Constraints\UniqueEntity;
use Symfony\Config\TwigExtra\StringConfig;


#[ORM\Entity(repositoryClass: UserRepository::class)]




/**
 * Summary of User
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface  
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   // #[ORM\Column(name: "googleAuthenticatorSecret", length: 255, nullable: true)]
    //private $googleAuthenticatorSecret;
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255,unique :true)]
    private ?string $username = null;

    #[ORM\Column(length: 255,unique :true)]
    private ?string $email = null;

    #[ORM\Column(name: 'mdp',length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?int $num_tel = null;

    #[ORM\Column(unique :true)]
    private ?int $CIN = null;

    #[ORM\Column(length: 255 , nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'id_user')]
    #[ORM\JoinColumn(nullable: false)]
    public ?Role $id_role = null;
    private $role = [];

    #[ORM\OneToMany(mappedBy: 'id_client', targetEntity: Reservation::class)]
    private Collection $id_reservation;

    #[ORM\ManyToOne(inversedBy: 'id_user')]
    #[ORM\JoinColumn(nullable: true)]
    public ?UserState $id_state = null;

    private ?string $token ;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getNumTel(): ?int
    {
        return $this->num_tel;
    }

    public function setNumTel(int $num_tel): self
    {
        $this->num_tel = $num_tel;

        return $this;
    }

    public function getCIN(): ?int
    {
        return $this->CIN;
    }

    public function setCIN(int $CIN): self
    {
        $this->CIN = $CIN;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;

    }

    public function getIdRole(): ?Role
    {
        return $this->id_role;
    }

    public function setIdRole(?Role $id_role): self
    {
        $this->id_role = $id_role;
        $this->role =[];
        $this->role[]=$id_role->getNom();
        array_unique($this->role);

        return $this;
    }
    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }
    public function state(User $user):bool{
        if($user->getIdState()==1){
            return false;
        }else{
            return true;
        }

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
            $idReservation->setIdClient($this);
        }

        return $this;
    }

    public function removeIdReservation(Reservation $idReservation): self
    {
        if ($this->id_reservation->removeElement($idReservation)) {
            // set the owning side to null (unless already changed)
            if ($idReservation->getIdClient() === $this) {
                $idReservation->setIdClient(null);
            }
        }

        return $this;
    }

    public function getIdState(): ?UserState
    {
        return $this->id_state;
    }

    public function setIdState(?UserState $id_state): self
    {
        $this->id_state = $id_state;

        return $this;
    }
    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles()
    {
        $this->role[]=$this->id_role->getNom();
        return array_unique($this->role);
        // guarantee every user at least has ROLE_USER
    }

    public function setRoles(Role $roles): self
    {

        $this->role[]=$roles->getNom();
        array_unique($this->role);
        return $this;
    }


    /**
     * Returning a salt is only needed if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    public function serializer(): array
    {
        $user=['id' => $this->getId(),
            'nom'=>$this->getNom(),
            'prenom'=>$this->getPrenom(),
            'username'=>$this->getUsername(),
            'email'=>$this->getEmail(),
            'num_tel'=>$this->getNumTel(),
            'image'=>$this->getImage(),
            'CIN'=>$this->getCIN(),
            'id_role' => $this->getIdRole()->getId(),
            'id_state' =>$this->getIdState()->getId(),
            'password' => $this->getPassword(),
            'id_role'=> $this->id_role->getId()];
        return $user;
    }
}
