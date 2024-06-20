<?php

namespace App\Controller;

use App\Entity\Result;
use App\Entity\Student;
use App\Form\StudentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/result', name: 'app_result_')]
class ResultController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
        
    }
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('result/index.html.twig', [
            'controller_name' => 'ResultController',
        ]);
    }
    #[Route('/{id}/addGrade', name: 'info', methods: ['GET', 'POST'])]
    public function addGrade(Request $request, Student $student): Response
    {
        // Créer une nouvelle entité Result
        $result = new Result();
        $result->setStudent($student);

        // Créer le formulaire
        // IL FAUT CREER LE RESULTTYPE
        $form = $this->createForm(StudentType::class, $result);

        // Traiter le formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer la note dans la base de données
            $this->em->persist($result);
            $this->em->flush();

            // Renvoyer une réponse
            return $this->redirectToRoute('view_student_grades', ['id' => $student->getId()]);
        }

        // Afficher le formulaire
        return $this->render('teacher/add_grade.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/showGrade', name: 'showGrades', methods: ['GET'])]
    public function viewStudentGrades(Student $student): Response
    {
        // Récupérer le repository de l'entité Result
        // Le ManagerRegistry $registry est utilisé pour accéder au gestionnaire d'entités de Doctrine
        $resultRepository = $this->em->getRepository(Result::class);

        // Utiliser le repository pour récupérer les notes de l'élève
        // La méthode findBy() est une méthode de Doctrine qui permet de récupérer des entités en fonction de certains critères
        // Ici, nous récupérons toutes les entités Result qui ont le même élève que celui passé en paramètre
        $grades = $resultRepository->findBy(['student' => $student]);

        // Renvoyer une réponse avec la vue des notes de l'élève
        // La méthode render() est une méthode de Symfony qui permet de générer une vue
        // Ici, nous générons une vue 'teacher/view_student_grades.html.twig' et nous lui passons les notes de l'élève
        return $this->render('student/view_student_grades.html.twig', [
            'student' => $student,
            'grades' => $grades,
        ]);
    }
}
