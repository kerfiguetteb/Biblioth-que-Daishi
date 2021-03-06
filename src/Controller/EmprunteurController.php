<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Emprunteur;
use App\Form\EmprunteurType;
use App\Repository\EmprunteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * @Route("/emprunteur")
 */
class EmprunteurController extends AbstractController
{
    /**
     * @Route("/", name="emprunteur_index", methods={"GET"})
     */
    public function index(Request $request, EmprunteurRepository $emprunteurRepository): Response
    {
        return $this->render('emprunteur/index.html.twig', [
            'emprunteurs' => $emprunteurRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="emprunteur_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $emprunteur = new Emprunteur();
        $form = $this->createForm(EmprunteurType::class, $emprunteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $emprunteur->getUser();
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('user')->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($emprunteur);
            $entityManager->flush();

            return $this->redirectToRoute('emprunteur_index');
        }

        return $this->render('emprunteur/new.html.twig', [
            'emprunteur' => $emprunteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="emprunteur_show", methods={"GET"})
     */
    public function show(Emprunteur $emprunteur, EmprunteurRepository $emprunteurRepository): Response
    {       
        
        $response = $this->redirectEmprunteur('emprunteur_show', $emprunteur, $emprunteurRepository);

        if ($response) {
            return $response;
        }

        return $this->render('emprunteur/show.html.twig', [
            'emprunteur' => $emprunteur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="emprunteur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Emprunteur $emprunteur, EmprunteurRepository $emprunteurRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // $response = $this->redirectEmprunteur('emprunteur_edit', $emprunteur, $emprunteurRepository);

        // if ($response) {
        //     return $response;
        // }

        $form = $this->createForm(EmprunteurType::class, $emprunteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user = $emprunteur->getUser();
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('user')->get('plainPassword')->getData()
                )
            );

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('emprunteur_index');
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
        if ($this->isGranted('ROLE_EMPRUNTEUR')) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$emprunteur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($emprunteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('emprunteur_index');
    }

    private function redirectEmprunteur(string $route, Emprunteur $emprunteur, EmprunteurRepository $emprunteurRepository)
    {
        // R??cup??ration du compte de l'utilisateur qui est connect??
        $user = $this->getUser();

        // On v??rifie si l'utilisateur est un emprunteur 
        // if (in_array('ROLE_EMPRUNTEUR', $user->getRoles())) {
            if ($this->isGranted('ROLE_EMPRUNTEUR',$user->getRoles())) {
                // R??cup??ration du profil emprunteur
            $userEmprunteur = $emprunteurRepository->findOneByUser($user);

            // Comparaison du profil demand?? par l'utilisateur et le profil de l'utilisateur
            // Si les deux sont diff??rents, on redirige l'utilisateur vers la page de son profil
            if ($emprunteur->getId() != $userEmprunteur->getId()) {
                return $this->redirectToRoute($route, [
                    'id' => $userEmprunteur->getId(),
                ]);
            }
        }

        // Si aucune redirection n'est n??cessaire, on renvoit une valeur nulle
        return null;
    }
}
