<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommanderRepository;
use App\Entity\Commander;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CommandesType;



class CommandesController extends AbstractController
{
    //afficher tte les commandes
    //---------------------------
    #[Route('/commandes', name: 'app_commandes',methods:['GET'])]
    public function index(CommanderRepository $commanderRepository): Response
    {
        return $this->render('commandes/all_commandes.html.twig', [
            'commandes' =>$commanderRepository->findAllCommandesWithJointures(),
        ]);
    }


    // Ajout commande
    //-----------------

    #[Route('/commandes/ajout', name: 'ajout_commande', methods:['GET', 'POST'])]
    public function ajout_commande(Request $request, EntityManagerInterface $entityManager,  CommanderRepository $commandesRepository ): Response
    {
        
        $commande = new Commander();
        $form = $this->createForm(CommandesType::class, $commande);
        $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid())
            {  
            $entityManager->persist($commande); // persist fait le lien entre l'ORM et symfony
            $entityManager->flush();              //flush fait le lien et applique les changements a la base de donnée
            return $this->redirectToRoute('app_commandes', [], Response::HTTP_SEE_OTHER);  // Redirigez l'utilisateur vers une autre page, par exemple la liste des livres
            }

        return $this->render('commandes/ajout_commande.html.twig', [
            'form' => $form->createView()
        ]);
    }


    //Update livre
    //--------------
    #[Route('/commandes{id}/update', name: 'update_commande',methods:['GET','POST'])]
    public function update_commande(int $id, Request $request, CommanderRepository $commandesRepository, EntityManagerInterface $entityManager): Response
    {
        $form= $this-> createForm(CommandesType::class, $commandesRepository->find($id));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

        return $this->redirectToRoute('app_commandes',[],
        Response::HTTP_SEE_OTHER);
        }
        return $this->render('commandes/update_commande.html.twig', [
        'form'=> $form, 'livre'=> $commandesRepository->findAll(),
        ]);
    }


    //delete commande 
    //--------------
    #[Route('/commandes/{id}/delete', name: 'delete_commande')]
    public function delete_commande( int $id, EntityManagerInterface $entityManager,  CommanderRepository $commanderRepository): Response
    {
        $commande = $commanderRepository->find($id);  //récupère le livre à partir de l'Id
        // var_dump($commande);
        $entityManager->remove($commande);

        $entityManager->flush();
        return $this->redirectToRoute('app_commandes');
    }

}
