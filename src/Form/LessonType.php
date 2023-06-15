<?php

namespace App\Form;

use App\Entity\Lesson;
use App\Entity\Training;
use App\Entity\User;
use App\Repository\LessonRepository;
use App\Repository\TrainingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessonType extends AbstractType
{
    public function __construct(
        private readonly TrainingRepository $trainingRepository,
        private readonly UserRepository $userRepository
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('location')
            ->add('capacity')
            ->add('training', TextType::class)
            ->add('user', TextType::class, ['required' => false])
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->add('saveAndExit', SubmitType::class, ['label' => 'Save & Exit'])
        ;

        $trainingRepository = $this->trainingRepository;

        $builder->get('training')->addModelTransformer(new CallbackTransformer(
            function(?Training $training): string {
                if(is_null($training)) return "";
                return $training->getId();
            },
            function($training_id) use($trainingRepository): ?Training {
                if($training_id == "") return null;
                return $trainingRepository->find($training_id);
            }
        ));

        $userRepository = $this->userRepository;

        $builder->get('user')->addModelTransformer(new CallbackTransformer(
            function(?User $user): string {
                if(is_null($user)) return "";
                return $user->getUsername();
            },
            function($user_id) use($userRepository): ?User {
                if($user_id == "") return null;
//                return $userRepository->find($user_id);
                $usersFound = $userRepository->findBy(['username' => $user_id]);
                if(sizeof($usersFound) > 0) {
                    return $usersFound[0];
                }
                return null;
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lesson::class,
        ]);
    }
}
