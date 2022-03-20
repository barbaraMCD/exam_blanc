<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Form\LivreType;
use App\Repository\LivresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(LivresRepository $livresRepository): Response
    {
        $livre = $livresRepository->findAll();
        return $this->render('home/index.html.twig', compact('livre'));
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, LivresRepository $livreRepository) : Response
    {
        $livres = new Livres();

        $form = $this->createForm(LivreType::class, $livres);
        $form->handleRequest($request);
        // permet d'associer le formulaire avec les informations envoyées

        if($form->isSubmitted() && $form->isValid())
        {
            $livreRepository->add($livres);
            return $this->redirectToRoute('home');
        }

        return $this->render("home/create.html.twig", [
          'form' => $form->createView()
        ]);
    }

    #[Route('/modify/{id<[0-9]+>}', name: 'edit', methods: ['GET', 'POST'])]
        public function edit(Livres $livres, Request $request,  LivresRepository $songRepository)
        {
            $form = $this->createForm(LivreType::class, $livres);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
        {
            $songRepository->add($livres);
            return $this->redirectToRoute('home');
        }

            return $this->render("home/edit.html.twig", [
                "form" => $form->createView()
            ]);
        }

        #[Route('/supprimer/{id<[0-9]+>}', name: 'delete.book', methods: ['POST'])]
        public function delete(Livres $livres, Request $request,  LivresRepository $livreRepository)
        {

            if($this->isCsrfTokenValid('delete' . $livres->getId(), $request->request->get('_token')) )
        {
            $livreRepository->remove($livres);
            return $this->redirectToRoute('home');
        }
        
        }
}
