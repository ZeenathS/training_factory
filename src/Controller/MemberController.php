<?php
    namespace App\Controller;
    use App\Form\UserType;
    use App\Repository\UserRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Bundle\SecurityBundle\Security;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

    class MemberController extends AbstractController
    {
        public function __construct(private readonly Security $security, private readonly UserRepository $userRepository) {}

        #[Route('/profile', name: 'app_profile')]
        public function index(): Response
        {
            return $this->render('member/index.html.twig', [
                'controller_name' => 'MemberController',
                'user' => $this->security->getUser(),
            ]);
        }

        #[Route('/edit-profile', name:'app_edit_profile')]
        public function editProfile(Request $request, AuthenticationUtils $authenticationUtils): Response
        {
            $user = $this->getUser();

            $form = $this->createForm(UserType::class, $user);
            $form->remove('Register');
            $form->remove('edit');
            $form->remove('password');
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user = $form->getData();
                $this->userRepository->save($user, true);

                if ($form->get('saveAndExit')->isClicked()) {
                    return $this->redirectToRoute('app_profile');
                }
            }

            return $this->render('member/edit.html.twig', [
                'form' => $form,
            ]);
        }
    }
?>