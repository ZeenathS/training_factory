<?php

namespace App\Controller;

use App\Entity\Training;
use App\Form\TrainingType;
use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/training')]
class TrainingManagerController extends AbstractController
{
    public function __construct(private readonly TrainingRepository $trainingRepository) {}

    #[Route('/', name: 'admin_training')]
    public function index(): Response
    {
        $trainings = $this->trainingRepository->findAll();

        return $this->render('admin/training/index.html.twig', [
            'controller_name' => 'TrainingController',
            'trainings' => $trainings,
        ]);
    }

    #[Route('/create', name: 'admin_training_create')]
    public function create(Request $request): Response {
        $training = new Training();
        return $this->handleEditTraining($training, $request);
    }

    #[Route('/edit/{training}', name: 'admin_training_edit')]
    public function edit(Training $training, Request $request): Response
    {
        return $this->handleEditTraining($training, $request);
    }

    #[Route('/delete/{training}', name: 'admin_training_delete')]
    public function delete(Training $training): Response
    {
        $this->trainingRepository->remove($training, true);

        return $this->redirectToRoute('admin_training');
    }


    private function handleEditTraining(Training $training, Request $request) : Response
    {
        $form = $this->createForm(TrainingType::class, $training);

        // Handle request to know if the form was submitted or not.
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Form was submitted and is valid.
            $training = $form->getData();
            $this->trainingRepository->save($training, true);

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('admin_training_edit', ['training' => $training->getId()]);
            }

            if($form->get('saveAndExit')->isClicked()) {
                return $this->redirectToRoute('admin_training');
            }
        }

        return $this->render('admin/training/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
