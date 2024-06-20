<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Homework;
use App\Form\HomeworkType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeworkController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * Cette méthode crée un nouveau dévoir.
     */
    #[Route('/homework/{id}/create-assignment', name: 'app_create_assignment', methods: ['GET', 'POST'])]
    public function createAssignment(Request $request, $subjectId, \Swift_Mailer $mailer): Response
    {
        // Créer une nouvelle entité Homework
        $homework = new Homework();

        // Créer le formulaire
        $form = $this->createForm(HomeworkType::class, $homework);

        // Traiter le formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le repository de l'entité Subject
            $subjectRepository = $this->em->getRepository(Course::class);

            // Utiliser le repository pour récupérer la matière
            $course = $subjectRepository->find($subjectId);

            // $homework->setSubject($course);

            // Enregistrer le devoir dans la base de données
            $this->em->persist($homework);
            $this->em->flush();

            // Créer le message
            $message = (new \Swift_Message('Nouveau devoir créé'))
                ->setFrom('noreply@yourschool.com')
                ->setBody(
                    $this->renderView(
                        'emails/new_homework.html.twig',
                        ['homework' => $homework]
                    ),
                    'text/html'
                );

            // Envoyer le message à chaque parent et élève
            /*   foreach ($students as $student) {
                  $message->setTo($student->getEmail());
                  $mailer->send($message);
              } */

            // Renvoyer une réponse
            return $this->redirectToRoute('view_assignments', ['id' => $subjectId]);
        }

        // Afficher le formulaire
        return $this->render('homework/create_assignment.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Cette méthode permet de télécharger un dévoir.
     */
    #[Route('/homework/{id}/download', name: 'app_download_homework')]
    public function downloadHomework(Homework $homework, \Dompdf\Dompdf $dompdf)
    {
        // Générer le HTML pour le devoir
        $html = $this->renderView('homework/pdf.html.twig', [
            'homework' => $homework,
        ]);

        // Charger le HTML dans DomPdf
        $dompdf->loadHtml($html);

        // Rendre le PDF
        $dompdf->render();

        // Générer la réponse avec le PDF
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;
    }

    /**
     * Cette méthode permet à un enseignant d'ajouter un devoir.
     */
    #[Route('/homework/{id}/addHomework', name: 'homework_add_homework', methods: ['GET', 'POST'])]
    public function addHomework(Request $request, homework $homework): Response
    {
        // Créez un nouvel objet Homework
        $homework = new Homework();

        // Créez le formulaire
        $form = $this->createForm(HomeworkType::class, $homework);

        // Gérez la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Liez le Homework à l'enseignant
            // $homework->sethomework($homework);

            // Validation supplémentaire...
            // if (!$homework->teachesClassroom($homework->getClassroom())) {
            //     throw new \Exception('This homework does not teach this classroom.');
            // }

            try {
                // Enregistrez le Homework dans la base de données
                // $entityManager = $this->managerRegistry->getManager();
                // $entityManager->persist($homework);
                // $entityManager->flush();

                // Log the action
                // $this->get('logger')->info('Homework created.', ['homework' => $homework]);

                // Envoyez une notification aux élèves et aux parents
                // $notificationService = new notificationService();
                // $notificationService->sendHomeworkNotification($homework);

                // Add a flash message
                // $this->addFlash('success', 'Homework created successfully.');
            } catch (\Exception $e) {
                // Handle the exception
                // $this->get('logger')->error('Error creating homework.', ['exception' => $e]);
                // $this->addFlash('error', 'Error creating homework.');
            }

            // Redirigez vers la page de l'enseignant
            return $this->redirectToRoute('homework_show', ['id' => $homework->getId()]);
        }

        // Affichez le formulaire
        return $this->render('homework/addHomework.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet à un enseignant ou un administrateur/personnel de l'enseignement.
     */
    #[Route('/homework/{id}/editHomework/{homeworkId}', name: 'homework_edit_homework', methods: ['GET', 'POST'])]
    public function editHomework(Request $request, homework $homework, int $homeworkId): Response
    {
        // Modifie un devoir à domicile
        return $this->render('homework/index.html.twig', [
            'controller_name' => 'homeworkController',
        ]);
    }

    /**
     * Permet à un personnel de supprimer un devoir.
     */
    #[Route('/homework/{id}/deleteHomework/{homeworkId}', name: 'homework_delete_homework', methods: ['DELETE'])]
    public function deleteHomework(Request $request, homework $homework, int $homeworkId): Response
    {
        // Supprime un devoir à domicile
        return $this->render('homework/index.html.twig', [
            'controller_name' => 'homeworkController',
        ]);
    }
}
