<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\UserEditType;
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
        $users = $this->userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users,
            'instructors' => $this->userRepository->getInstructors(),
        ]);
    }

    #[Route('/add-instructor/{user}', name: 'add_instructor', methods: ['POST'])]
    public function addInstructor(User $user, Request $request): Response
    {
        $user->addRole('ROLE_INSTRUCTOR');
        $this->userRepository->save($user, true);

        if($request->query->has('redirect')) {
            return $this->redirect($request->query->get('redirect'));
        }

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/remove-instructor/{user}', name: 'remove_instructor', methods: ['POST'])]
    public function removeInstructor(User $user, Request $request): Response
    {
        $user->removeRole('ROLE_INSTRUCTOR');
        $this->userRepository->save($user, true);

        if($request->query->has('redirect')) {
            return $this->redirect($request->query->get('redirect'));
        }

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/users', name: 'admin_users')]
    public function getAllUsers(): Response
    {
        $users = $this->userRepository->findAll();
        return $this->render('admin/user/index.html.twig', ['users' => $users]);
    }

    #[Route('/user-edit/{user}', name: 'admin_edit_user')]
    public function editUsers(User $user, Request $request): Response
    {

        $form = $this->createForm(UserEditType::class, $user);
        $form->remove('password');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $this->userRepository->save($user, true);

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('admin_edit_user', ['user' => $user->getId()]);
            }

            if ($form->get('saveAndExit')->isClicked()) {
                return $this->redirectToRoute('admin_users');
            }
        }
        return $this->render('admin/user/user.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/remove-user/{user}', name: 'admin_delete_user')]
    public function deleteUser(User $user): Response
    {
        $this->userRepository->remove($user, true);
        return $this->redirectToRoute('admin_users');
    }
}
