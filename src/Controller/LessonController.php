<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\Registration;
use App\Repository\RegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LessonController extends AbstractController
{
    #[Route('/lesson/{lesson}/register', name: 'app_lesson_register')]
    #[IsGranted('IS_AUTHENTICATED')]
    public function registerForLesson(Lesson $lesson, Security $security, RegistrationRepository $registrationRepository): Response
    {
        // If user is not already registered to the lesson.
        if(
            !$lesson->getRegistrations()->exists(function($_, Registration $registration) use($security) {
                return $registration->getUser() === $security->getUser();
            })
        ) {
            // Then register them to the lesson.
            $registration = new Registration();
            $registration->setUser($security->getUser());
            $registration->setLesson($lesson);

            $registrationRepository->save($registration, true);
        }

        return $this->redirectToRoute('app_training_lessons', ['training' => $lesson->getTraining()->getId()]);
    }

    #[Route('/lesson/{lesson}/unregister', name: 'app_lesson_unregister')]
    #[IsGranted('IS_AUTHENTICATED')]
    public function unregisterForLesson(Lesson $lesson, Security $security, RegistrationRepository $registrationRepository): Response
    {
        if(
            $lesson->getRegistrations()->exists(function($_, Registration $registration) use($security) {
                return $registration->getUser() === $security->getUser();
            })
        ) {
            $registration = $lesson->getRegistrations()->findFirst(function($_, Registration $registration) use($security) {
                return $registration->getUser() === $security->getUser();
            });
            $registrationRepository->remove($registration, true);
        }

        return $this->redirectToRoute('app_training_lessons', ['training' => $lesson->getTraining()->getId()]);
    }
}
