<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    public function __construct(private readonly UserRepository $userRepository) {}

    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
//        return $this->json($this->userRepository->getInstructors());

        $users = $this->userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users,
            'instructors' => $this->userRepository->getInstructors(),
        ]);
    }

    #[Route('/add-instructor/{user}', name: 'add_instructor', methods: ['POST'])]
    public function addInstructor(User $user): Response
    {
        $user->addRole('ROLE_INSTRUCTOR');
        $this->userRepository->save($user, true);

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/remove-instructor/{user}', name: 'remove_instructor', methods: ['POST'])]
    public function removeInstructor(User $user): Response
    {
        $user->removeRole('ROLE_INSTRUCTOR');
        $this->userRepository->save($user, true);

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/user-edit', name: 'admin_edit_user')]
    public function editUsers(User $user, Request $request): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $this->userRepository->save($user, true);

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('admin_lesson_edit', ['lesson' => $lesson->getId()]);
            }

            if ($form->get('saveAndExit')->isClicked()) {
                return $this->redirectToRoute('admin_lesson');
            }
        }
        return $this->render('admin/lesson/edit.html.twig', [
            'form' => $form,
            'registrations' => $user->getRegistrations()
        ]);
    }
}
