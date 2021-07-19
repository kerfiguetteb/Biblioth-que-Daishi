<?php

namespace App\Controller;

use App\Entity\Emprunteur;
use App\Form\EmprunteurType;
use App\Repository\EmprunteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/emprunteur")
 */
class EmprunteurController extends AbstractController
{
    /**
     * @Route("/", name="emprunteur_index", methods={"GET"})
     */
    public function index(EmprunteurRepository $emprunteurRepository): Response
    {
        return $this->render('emprunteur/index.html.twig', [
            'emprunteurs' => $emprunteurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="emprunteur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $emprunteur = new Emprunteur();
        $form = $this->createForm(EmprunteurType::class, $emprunteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($emprunteur);
            $entityManager->flush();

            return $this->redirectToRoute('emprunteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emprunteur/new.html.twig', [
            'emprunteur' => $emprunteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="emprunteur_show", methods={"GET"})
     */
    public function show(Emprunteur $emprunteur): Response
    {
        return $this->render('emprunteur/show.html.twig', [
            'emprunteur' => $emprunteur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="emprunteur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Emprunteur $emprunteur): Response
    {
        $form = $this->createForm(EmprunteurType::class, $emprunteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('emprunteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emprunteur/edit.html.twig', [
            'emprunteur' => $emprunteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="emprunteur_delete", methods={"POST"})
     */
    public function delete(Request $request, Emprunteur $emprunteur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emprunteur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($emprunteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('emprunteur_index', [], Response::HTTP_SEE_OTHER);
    }
}
