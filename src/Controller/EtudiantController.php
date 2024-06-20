<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/etudiant', name: 'app_etudiant_')]
class EtudiantController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private EtudiantRepository $repo
    ) {
    }

    #[Route('/index/{id}', name: 'index')]
    public function index(Etudiant $etudiant): Response
    {
        // $etudiant = $this->repo->findAll();
        dd($etudiant);

        return $this->render('etudiant/index.html.twig', ['etudiant' => $etudiant]);
    }

    #[Route('/{id}/showDetails', name: 'info', methods: ['GET'])]
    public function getetudiantInfo(etudiant $etudiant)
    {
        $user = $this->getUser();

        if ($user instanceof Enseignant) {
            if (!$user->canViewStudent($etudiant)) {
                throw new AccessDeniedException('Vous n\'avez pas le droit de consulter les informations de cet élève.');
            }
        }

        if ($user instanceof etudiant) {
            if ($user->getId() !== $etudiant->getId()) {
                throw new AccessDeniedException('Vous n\'avez pas le droit de consulter les informations d\'un autre élève.');
            }
        }

        return $this->render('etudiant/info.html.twig', ['etudiant' => $etudiant]);
    }
}
