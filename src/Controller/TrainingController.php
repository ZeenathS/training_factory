<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\Training;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/training')]
class TrainingController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    #[Route('/', name: 'app_training')]
    public function index(): Response
    {
        $trainings = $this->entityManager->getRepository(Training::class)->findAll();

        return $this->render('training/index.html.twig', [
            'controller_name' => 'TrainingController',
            'trainings' => $trainings
        ]);
    }

    #[Route('/lessons/{training}', name:'app_training_lessons')]
    public function lessonsForTraining(Training $training): Response
    {
        $lessons = $training->getLesson()->filter(function(Lesson $lesson) {
            return $lesson->getRegistrations()->count() < $lesson->getCapacity();
        });

        return $this->render('training/lessons.html.twig', [
            'lessons' => $lessons,
            'user' => $this->getUser()
        ]);
    }
}
