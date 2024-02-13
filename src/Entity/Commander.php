<?php

namespace App\Entity;

use App\Repository\CommanderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommanderRepository::class)]
class Commander
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"id_commande")]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false,name:"Id_Livre", referencedColumnName:"Id_Livre",)]
    private ?Livres $Id_Livre = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false,name:"Id_fournisseur", referencedColumnName:"Id_fournisseur",)]
    private ?Fournisseurs $Id_fournisseur = null;

    #[ORM\Column(length: 50)]
    private ?string $Date_achat = null;

    #[ORM\Column(length: 50)]
    private ?string $Prix_achat = null;

    #[ORM\Column(length: 50)]
    private ?string $Nbr_exemplaires = null;

    // #[ORM\ManyToOne(targetEntity: self::class)]
    // #[ORM\JoinColumn(name:"idUtilisateur",nullable: false)]
    // private ?self $idUtilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdLivre(): ?Livres
    {
        return $this->Id_Livre;
    }

    public function setIdLivre(?Livres $Id_Livre): static
    {
        $this->Id_Livre = $Id_Livre;

        return $this;
    }

    public function getIdFournisseur(): ?Fournisseurs
    {
        return $this->Id_fournisseur;
    }

    public function setIdFournisseur(?Fournisseurs $Id_fournisseur): static
    {
        $this->Id_fournisseur = $Id_fournisseur;

        return $this;
    }

    public function getDateAchat(): ?string
    {
        return $this->Date_achat;
    }

    public function setDateAchat(string $Date_achat): static
    {
        
        $this->Date_achat = $Date_achat;

        return $this;
    }

    public function getPrixAchat(): ?string
    {
        return $this->Prix_achat;
    }

    public function setPrixAchat(string $Prix_achat): static
    {
        $this->Prix_achat = $Prix_achat;

        return $this;
    }

    public function getNbrExemplaires(): ?string
    {
        return $this->Nbr_exemplaires;
    }

    public function setNbrExemplaires(string $Nbr_exemplaires): static
    {
        $this->Nbr_exemplaires = $Nbr_exemplaires;

        return $this;
    }

    // public function getIdUtilisateur(): ?self
    // {
    //     return $this->idUtilisateur;
    // }

    // public function setIdUtilisateur(?self $idUtilisateur): static
    // {
    //     $this->idUtilisateur = $idUtilisateur;

    //     return $this;
    // }
}
