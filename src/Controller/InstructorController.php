<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/instructor')]
class InstructorController extends AbstractController
{
//    public function __construct(private readonly InstructorRepository $instructorRepository) {}

    #[Route('/', name: 'app_instructors')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $instructors = $entityManager->getRepository(User::class)->findAll();

        return $this->render('instructor/index.html.twig', [
            'instructors' => $instructors
        ]);
    }

    #[Route('/{instructor_id}', name: 'app_instructor')]
    public function instructor(int $instructor_id, EntityManagerInterface $entityManager): Response
    {
        $instructor = $entityManager->getRepository(User::class)->find();

        return $this->render('instructor/instructor.html.twig', [
            'controller_name' => 'InstructorController',
            'instructor' => $instructor
        ]);
    }

    // Create Instructor
    #[Route('/create', name: 'create')]
    public function createInstructor(EntityManagerInterface $entityManager): Response {
//
//        $dash = new User();
//        $dash->setUser('Dash');
//        $dash->setHiringDate('07-06-2023');
//        $dash->setSalary('885.15');
//        $dash->setSocialSecNumber(1234586);
//
//        $entityManager->persist($dash);
//        $entityManager->flush();

//        $form = $this->createForm()

        return $this->render('instructor/create.html.twig');
    }

    // Update Instructor

    // Delete Instructor
}
