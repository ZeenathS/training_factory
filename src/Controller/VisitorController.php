<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\Training;
use App\Repository\LessonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VisitorController extends AbstractController
{
    #[Route('/', name: 'app_visitor')]
    public function index(): Response
    {
        return $this->render('visitor/index.html.twig', [
            'controller_name' => 'VisitorController',
        ]);
    }

    #[Route('/regulations', name: 'regulations')]
    public function regulations(): Response
    {
        return $this->render('visitor/regulations.html.twig', [
            'controller_name' => 'VisitorController',
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contactPage(): Response
    {
        return $this->render('visitor/contact.html.twig', [
            'controller_name' => 'VisitorController',
        ]);
    }
}
