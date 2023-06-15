<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\Registration;
use App\Form\LessonType;
use App\Repository\LessonRepository;
use App\Repository\RegistrationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/lesson')]
class LessonManagerController extends AbstractController
{
    public function __construct(private readonly LessonRepository $lessonRepository) {}

    #[Route('/', name: 'admin_lesson')]
    public function index(): Response
    {
        $lessons = $this->lessonRepository->findAll();

        return $this->render('admin/lesson/index.html.twig', [
            'controller' => 'LessonManagerController',
            'lessons' => $lessons
        ]);
    }

    #[Route('/create', name:'admin_lesson_create')]
    public function create(Request $request): Response
    {
        $lesson = new Lesson();
        return $this->handleEditLesson($lesson, $request);
    }

    #[Route('/edit/{lesson}', name:'admin_lesson_edit')]
    public function edit(Lesson $lesson, Request $request): Response
    {
        return $this->handleEditLesson($lesson, $request);
    }

    #[Route('/delete/{lesson}', name:'admin_lesson_delete')]
    public function delete(Lesson $lesson, Request $request): Response
    {
        $this->lessonRepository->remove($lesson, true);

        return $this->redirectToRoute('admin_lesson');
    }

    public function handleEditLesson(Lesson $lesson, Request $request): Response
    {
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $lesson = $form->getData();
            $this->lessonRepository->save($lesson, true);

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('admin_lesson_edit', ['lesson' => $lesson->getId()]);
            }

            if ($form->get('saveAndExit')->isClicked()) {
                return $this->redirectToRoute('admin_lesson');
            }
        }
        return $this->render('admin/lesson/edit.html.twig', [
            'form' => $form,
            'registrations' => $lesson->getRegistrations()
        ]);
    }

    #[Route('/registrations/{registration}/remove', name: 'admin_lesson_registrations_remove')]
    public function unregisterUserForLesson(Registration $registration, RegistrationRepository $registrationRepository): Response
    {
        $lesson = $registration->getLesson();
        $registrationRepository->remove($registration, true);
        return $this->redirectToRoute('admin_lesson_edit', ['lesson' => $lesson->getId()]);
    }
}
