<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClasseRepository::class)]
class Classe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $designation = null;

    #[ORM\ManyToOne(inversedBy: 'classes')]
    private ?Filiere $filiere = null;

    #[ORM\ManyToMany(targetEntity: Enseignant::class, inversedBy: 'classes')]
    private Collection $enseignant;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: Etudiant::class)]
    private Collection $etudiant;

    #[ORM\ManyToMany(targetEntity: Travail::class, inversedBy: 'classes')]
    private Collection $travail;

    #[ORM\ManyToMany(targetEntity: Enseignant::class, mappedBy: 'classes')]
    private Collection $enseignants;

    public function __construct()
    {
        $this->enseignant = new ArrayCollection();
        $this->etudiant = new ArrayCollection();
        $this->travail = new ArrayCollection();
        $this->enseignants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getFiliere(): ?Filiere
    {
        return $this->filiere;
    }

    public function setFiliere(?Filiere $filiere): static
    {
        $this->filiere = $filiere;

        return $this;
    }

    /**
     * @return Collection<int, Enseignant>
     */
    public function getEnseignant(): Collection
    {
        return $this->enseignant;
    }

    public function addEnseignant(Enseignant $enseignant): static
    {
        if (!$this->enseignant->contains($enseignant)) {
            $this->enseignant->add($enseignant);
        }

        return $this;
    }

    public function removeEnseignant(Enseignant $enseignant): static
    {
        $this->enseignant->removeElement($enseignant);

        return $this;
    }

    /**
     * @return Collection<int, Etudiant>
     */
    public function getEtudiant(): Collection
    {
        return $this->etudiant;
    }

    public function addEtudiant(Etudiant $etudiant): static
    {
        if (!$this->etudiant->contains($etudiant)) {
            $this->etudiant->add($etudiant);
            $etudiant->setClasse($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): static
    {
        if ($this->etudiant->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getClasse() === $this) {
                $etudiant->setClasse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Travail>
     */
    public function getTravail(): Collection
    {
        return $this->travail;
    }

    public function addTravail(Travail $travail): static
    {
        if (!$this->travail->contains($travail)) {
            $this->travail->add($travail);
        }

        return $this;
    }

    public function removeTravail(Travail $travail): static
    {
        $this->travail->removeElement($travail);

        return $this;
    }

    /**
     * @return Collection<int, Enseignant>
     */
    public function getEnseignants(): Collection
    {
        return $this->enseignants;
    }
}
