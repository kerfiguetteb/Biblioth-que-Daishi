<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Form\EmpruntType;
use App\Repository\EmpruntRepository;
use App\Repository\EmprunteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @Route("/emprunt")
 */
class EmpruntController extends AbstractController
{
    /**
     * @Route("/", name="emprunt_index", methods={"GET"})
     */
    public function index(EmpruntRepository $empruntRepository, EmprunteurRepository $emprunteurRepository, Request $request): Response
    {
        $user = $this->getUser();
        $emprunts = $empruntRepository->findAll();

        if ($this->isGranted('ROLE_EMPRUNTEUR')) {
            $emprunteur = $emprunteurRepository->findOneByUser($user);
            $emprunts = $emprunteur->getEmprunts();
            
            return $this->render('emprunt/index.html.twig', [
            'emprunts' => $emprunts,
        ]);
    }
        elseif ($this->isGranted('ROLE_ADMIN')) {

            return $this->render('emprunt/index.html.twig', [
                'emprunts' => $emprunts,
            ]);
    }

    }

    /**
     * @Route("/new", name="emprunt_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $emprunt = new Emprunt();
        $form = $this->createForm(EmpruntType::class, $emprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($emprunt);
            $entityManager->flush();

            return $this->redirectToRoute('emprunt_index');
        }

        return $this->render('emprunt/new.html.twig', [
            'emprunt' => $emprunt,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="emprunt_show", methods={"GET"})
     */
    public function show(Emprunt $emprunt, EmprunteurRepository $emprunteurRepository): Response
    { 
        
        if ($this->isGranted('ROLE_EMPRUNTEUR')) {

            $user = $this->getUser();
            $emprunteur = $emprunteurRepository->findOneByUser($user);
            if (!$emprunteur->getEmprunts()->contains($emprunt)) {
                throw new NotFoundHttpException();
            }
        }
        return $this->render('emprunt/show.html.twig', [
            'emprunt' => $emprunt,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="emprunt_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Emprunt $emprunt): Response
    {
        $form = $this->createForm(EmpruntType::class, $emprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('emprunt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emprunt/edit.html.twig', [
            'emprunt' => $emprunt,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="emprunt_delete", methods={"POST"})
     */
    public function delete(Request $request, Emprunt $emprunt): Response
    {        if ($this->isGranted('ROLE_EMPRUNTEUR')) {
        throw new AccessDeniedException();
    }

        if ($this->isCsrfTokenValid('delete'.$emprunt->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($emprunt);
            $entityManager->flush();
        }

        return $this->redirectToRoute('emprunt_index');
    }
}
