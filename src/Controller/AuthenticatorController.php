<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/auth')]
class AuthenticatorController extends AbstractController
{
    #[Route('/sign-in', name: 'signIn')]
    public function login(AuthenticationUtils $authenticationUtils): Response {
        if($this->isGranted('IS_AUTHENTICATED')) {
            return $this->redirectToRoute('app_member');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('authenticator/sign-in.html.twig', [
            'controller_name' => 'MemberController',
            'lastUsername' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/register', name: 'register')]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        Security $security,
    )
    : Response {
        if($security->isGranted('IS_AUTHENTICATED')) {
            return $this->redirectToRoute('app_member');
        }

        $user = new User();
        $user->setRoles(['ROLE_MEMBER']);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            //hash password
            $hashedPassword = $passwordHasher->hashPassword($user, '');
            $user->setPassword($hashedPassword);
            $entityManager->persist($user);
            $entityManager->flush();

            $security->login($user);

            return $this->redirectAction($security);
        }

        return $this->render('authenticator/register.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/logout', name: 'logout')]
    public function logout(): Response {
        throw new \Exception('Don\'t  forget to activate logout in \'config/packages/security.yaml\'');
    }

    public function redirectAction(Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin');
        }
        if ($security->isGranted('ROLE_MEMBER')) {
            return $this->redirectToRoute('app_member');
        }
        if ($security->isGranted('ROLE_INSTRUCTOR')) {
            return $this->redirectToRoute('app_instructors');
        }
        return $this->redirectToRoute('app_visitor');
    }
}
