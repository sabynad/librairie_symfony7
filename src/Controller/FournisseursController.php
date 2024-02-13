<?php

namespace App\Controller;

use App\Entity\Fournisseurs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FournisseursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\FournisseursType;

class FournisseursController extends AbstractController

{

    //afficher tout les fournisseurs
    //-------------------------------
    #[Route('/fournisseurs', name: 'app_fournisseurs',methods:['GET'])]
    public function index(FournisseursRepository $fournisseursRepository): Response
    {
        return $this->render('fournisseurs/all_fournisseurs.html.twig', [
                'fournisseurs'=>$fournisseursRepository->findAll(),
            ]);
    }

    //Update fournisseur
    //--------------
    #[Route('/fournisseurs{id}/update', name: 'update_fournisseur',methods:['GET','POST'])]
    public function update_fournisseur(int $id, Request $request, FournisseursRepository $fournisseursRepository, EntityManagerInterface $entityManager): Response
    {
        $form= $this-> createForm(FournisseursType::class, $fournisseursRepository->find($id));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

        return $this->redirectToRoute('app_fournisseurs',[],
        Response::HTTP_SEE_OTHER);
        }
        return $this->render('fournisseurs/update_fournisseur.html.twig',
        [
         'form'=> $form, 'fournisseur'=> $fournisseursRepository->findAll(),
        ]);
    }


    //Ajout fournisseur
    //------------------
    #[Route('/fournisseurs/ajout', name: 'ajout_fournisseur', methods:['GET', 'POST'])]
    public function ajout_fournisseur(Request $request, EntityManagerInterface $entityManager,  FournisseursRepository $fournisseursRepository ): Response
        {
            
            $fournisseur = new Fournisseurs();
            $form = $this->createForm(FournisseursType::class, $fournisseur);
            $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid())
                {  
                $entityManager->persist($fournisseur); // persist fait le lien entre l'ORM et symfony
                $entityManager->flush();              //flush fait le lien et applique les changements   dans la base de donnée
                return $this->redirectToRoute('app_fournisseurs', [], Response::HTTP_SEE_OTHER);  // Redirigez l'utilisateur vers une autre page, par exemple la liste des fournisseurs
                }

                return $this->render('fournisseurs/ajout_fournisseur.html.twig', [
                   'form' => $form->createView()
                ]);
        }



    //delete livre 
    //--------------
    #[Route('/fournisseurs/{id}/delete', name: 'delete_fournisseur')]
    public function delete_fournisseur( int $id, EntityManagerInterface $entityManager, FournisseursRepository $fournisseursRepository): Response
    {
        $fournisseur = $fournisseursRepository->find($id);  //récupère le livre à partir de l'Id
        var_dump($fournisseur);
        $entityManager->remove($fournisseur);

        $entityManager->flush();
        return $this->redirectToRoute('app_fournisseurs');
    }


}
