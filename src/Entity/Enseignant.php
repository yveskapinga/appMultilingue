<?php

namespace App\Entity;

use App\Repository\EnseignantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnseignantRepository::class)]
class Enseignant extends User
{

    #[ORM\Column(length: 20)]
    private ?string $cin = null;

    #[ORM\OneToMany(mappedBy: 'enseignant', targetEntity: Module::class)]
    private Collection $modules;

    #[ORM\OneToMany(mappedBy: 'enseignant', targetEntity: Travail::class)]
    private Collection $travails;

    #[ORM\ManyToMany(targetEntity: Classe::class, inversedBy: 'enseignants')]
    private Collection $classes;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
        $this->travails = new ArrayCollection();
        $this->classes = new ArrayCollection();
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * @return Collection<int, Module>
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addModule(Module $module): self
    {
        if (!$this->modules->contains($module)) {
            $this->modules->add($module);
            $module->setEnseignant($this);
        }

        return $this;
    }

    public function removeModule(Module $module): self
    {
        if ($this->modules->removeElement($module)) {
            // set the owning side to null (unless already changed)
            if ($module->getEnseignant() === $this) {
                $module->setEnseignant(null);
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
            $travail->setEnseignant($this);
        }

        return $this;
    }

    public function removeTravail(Travail $travail): static
    {
        if ($this->travails->removeElement($travail)) {
            // set the owning side to null (unless already changed)
            if ($travail->getEnseignant() === $this) {
                $travail->setEnseignant(null);
            }
        }

        return $this;
    }

    public function canViewStudent(Etudiant $etudiant): bool
    {
        $classEtudiant = $etudiant->getClasse();

        foreach ($this->getClasses() as $classe) {
            if ($classe === $classEtudiant) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Collection<int, Classe>
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classe $class): static
    {
        if (!$this->classes->contains($class)) {
            $this->classes->add($class);
        }

        return $this;
    }

    public function removeClass(Classe $class): static
    {
        $this->classes->removeElement($class);

        return $this;
    }
}
