<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    #[Assert\Type("datetime")]
    #[Assert\GreaterThanOrEqual("today")]
    private $dateHeureDepart;

    #[ORM\Column(type: 'datetime')]
    #[Assert\Type("datetime")]
    #[Assert\GreaterThan(propertyPath:"dateHeureDepart")]
    private $dateHeureFin;

    #[ORM\Column(type: 'integer')]
    #[Assert\GreaterThan(value:30)]
    private $prixTotal;

    #[ORM\Column(type: 'datetime')]
    #[Assert\Type("datetime")]
    private $dateEnregistrement;

    #[ORM\ManyToOne(targetEntity:Vehicule::class, inversedBy:"commandes", cascade:["persist"])]
    private $vehicule;

    #[ORM\ManyToOne(targetEntity:User::class, inversedBy:"commandes", cascade:["persist"])]
    private $user;

    public function __construct()
    {
        $tz = new \DateTimeZone('Europe/Paris');
        $now = new \DateTime();
        $now->setTimezone($tz);	
        $this->setDateEnregistrement($now);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateHeureDepart(): ?\DateTimeInterface
    {
        return $this->dateHeureDepart;
    }

    public function setDateHeureDepart(\DateTimeInterface $dateHeureDepart): self
    {
        $this->dateHeureDepart = $dateHeureDepart;

        return $this;
    }

    public function getDateHeureFin(): ?\DateTimeInterface
    {
        return $this->dateHeureFin;
    }

    public function setDateHeureFin(\DateTimeInterface $dateHeureFin): self
    {
        $this->dateHeureFin = $dateHeureFin;

        return $this;
    }

    public function getPrixTotal(): ?int
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(int $prixTotal): self
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getDateEnregistrement(): ?\DateTimeInterface
    {
        return $this->dateEnregistrement;
    }

    public function setDateEnregistrement(\DateTimeInterface $dateEnregistrement): self
    {
        $this->dateEnregistrement = $dateEnregistrement;

        return $this;
    }

    public function getVehicule(): ?Vehicule
    {
        return $this->vehicule;
    }

    public function setVehicule(?Vehicule $vehicule): self
    {
        $this->vehicule = $vehicule;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
