<?php
    namespace App\Controller;
    use App\Entity\User;
    use App\Form\UserType;
    use App\Repository\UserRepository;
    use App\Repository\RegistrationRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Bundle\SecurityBundle\Security;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

    class MemberController extends AbstractController
    {
        public function __construct(private readonly Security $security, private readonly RegistrationRepository $registrationRepository, private readonly UserRepository $userRepository) {}

        #[Route('/profile', name: 'app_profile')]
        public function index(UserRepository $userRepository): Response
        {

            $user = $this->getUser();
            return $this->render('member/index.html.twig', [
                'controller_name' => 'MemberController',
                'user' => $user,
            ]);
        }

    #[Route('/member', name: 'app_member')]
    public function index(): Response
    {
        return $this->render('member/index.html.twig', [
            'controller_name' => 'MemberController',
        ]);
    }
}
?>