<?php

namespace App\Controller;

use App\Entity\Enseignant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnseignantController extends AbstractController
{
    #[Route('/enseignant/{id}', name: 'app_enseignant')]
    public function index(Enseignant $enseignant): Response
    {
        return $this->render('enseignant/index.html.twig', [
            'enseignant' => $enseignant,
        ]);
    }
}
