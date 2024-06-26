<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'modules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Enseignant $enseignant = null;

    #[ORM\ManyToOne(inversedBy: 'modules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Filiere $filiere = null;

    #[ORM\ManyToOne(inversedBy: 'modules')]
    private ?Semestre $semestre = null;

    #[ORM\OneToMany(mappedBy: 'module', targetEntity: Note::class)]
    private Collection $notes;

    #[ORM\OneToMany(mappedBy: 'module', targetEntity: Travail::class)]
    private Collection $travails;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->travails = new ArrayCollection();
    }

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

    public function getEnseignant(): ?enseignant
    {
        return $this->enseignant;
    }

    public function setEnseignant(?enseignant $enseignant): self
    {
        $this->enseignant = $enseignant;

        return $this;
    }

    public function getFiliere(): ?filiere
    {
        return $this->filiere;
    }

    public function setFiliere(?filiere $filiere): self
    {
        $this->filiere = $filiere;

        return $this;
    }

    public function getSemestre(): ?semestre
    {
        return $this->semestre;
    }

    public function setSemestre(?semestre $semestre): self
    {
        $this->semestre = $semestre;

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setModule($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getModule() === $this) {
                $note->setModule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Travail>
     */
    public function getTravails(): Collection
    {
        return $this->travails;
    }

    public function addTravail(Travail $travail): static
    {
        if (!$this->travails->contains($travail)) {
            $this->travails->add($travail);
            $travail->setModule($this);
        }

        return $this;
    }

    public function removeTravail(Travail $travail): static
    {
        if ($this->travails->removeElement($travail)) {
            // set the owning side to null (unless already changed)
            if ($travail->getModule() === $this) {
                $travail->setModule(null);
            }
        }

        return $this;
    }
}
