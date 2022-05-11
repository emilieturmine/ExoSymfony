<?php

namespace App\Controller;
use App\Entity\artist;
use App\Entity\Disc;
use App\Form\DiscType;
use App\Repository\DiscRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/disc')]
class DiscController extends AbstractController
{
    #[Route('/', name: 'app_disc_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $discs = $entityManager
            ->getRepository(Disc::class)
            ->findAll();

        return $this->render('disc/index.html.twig', [
            'discs' => $discs,
        ]);
    }

    #[Route('/new', name: 'app_disc_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $disc = new Disc();
        $form = $this->createForm(DiscType::class, $disc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($disc);
            $entityManager->flush();

            return $this->redirectToRoute('app_disc_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('disc/new.html.twig', [
            'disc' => $disc,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_disc_show', methods: ['GET'])]
    public function show(Disc $disc): Response
    {
        return $this->render('disc/show.html.twig', [
            'disc' => $disc,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_disc_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Disc $disc, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DiscType::class, $disc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_disc_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('disc/edit.html.twig', [
            'disc' => $disc,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_disc_delete', methods: ['POST'])]
    public function delete(Request $request, Disc $disc, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$disc->getId(), $request->request->get('_token'))) {
            $entityManager->remove($disc);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_disc_index', [], Response::HTTP_SEE_OTHER);
    }
}
