<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Form\LivresType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LivresRepository;      //accès aux données des livres.
use Doctrine\ORM\EntityManagerInterface;  // conserve les modifications
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;  // Cela signifie que vous pouvez utiliser `ChoiceType` dans votre fichier sans avoir à spécifier le chemin complet vers la classe à chaque fois que vous en avez besoin.
use App\Form\LivresTitreType;
use App\Form\LivresEditeurType;

class LivresController extends AbstractController
{

    //afficher tout les livres
    //-------------------------
        #[Route('/livres', name: 'app_livres',methods:['GET'])]
        public function index(LivresRepository $livresRepository): Response
        {
            return $this->render('livres/all_livres.html.twig', [
                    'livres'=>$livresRepository->findAll(),
                ]);
        }


    //view livre par ligne
    //------------------------
        // #[Route('/livres/{id}', name: 'edit_livre',methods:['GET'])]
        // public function livreEdit(int $id, LivresRepository $livreRepository)
        // {
        //     $livre= $livreRepository->find($id);
        //     // var_dump($livre);
        //     return $this->render('livres/edit_livre.html.twig',[
        //         'livre'=>$livre,
        //     ]);
        // }

    
    //Update livre
    //--------------
        #[Route('/livres{id}/update', name: 'update_livre',methods:['GET','POST'])]
        public function edit_livre(int $id, Request $request, LivresRepository $livresRepository, EntityManagerInterface $entityManager): Response
        {
        $form= $this-> createForm(LivresType::class, $livresRepository->find($id));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

        return $this->redirectToRoute('app_livres',[],
        Response::HTTP_SEE_OTHER);
        }
        return $this->render('livres/update_livre.html.twig', [
        'form'=> $form, 'livre'=> $livresRepository->findAll(),
        ]);
    }


    //Ajout livre
    //-------------
    #[Route('/livres/ajout', name: 'ajout_livre', methods:['GET', 'POST'])]
        public function ajout_livre(Request $request, EntityManagerInterface $entityManager,  LivresRepository $livresRepository ): Response
        {
            
            $livre = new Livres();
            $form = $this->createForm(LivresType::class, $livre);
            $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
                {  
                $entityManager->persist($livre); // persist fait le lien entre l'ORM et symfony
                $entityManager->flush();              //flush fait le lien et applique les changements a la base de donnée
                return $this->redirectToRoute('app_livres', [], Response::HTTP_SEE_OTHER);  // Redirigez l'utilisateur vers une autre page, par exemple la liste des livres
                }

            return $this->render('livres/ajout_livre.html.twig', [
                'form' => $form->createView()
            ]);
        }



    //delete livre 
    //--------------
    #[Route('/livres/{id}/delete', name: 'delete_livre')]
        public function delete_livre( int $id, EntityManagerInterface $entityManager,  LivresRepository $livresRepository): Response
        {
            $livre = $livresRepository->find($id);  //récupère le livre à partir de l'Id
            var_dump($livre);
            $entityManager->remove($livre);

            $entityManager->flush();
            return $this->redirectToRoute('app_livres');
        }



    // Selection livre par titre
    // --------------------------
        #[Route('/livres/titre', name: 'livres_Titre', methods: ['GET','POST'])]
            public function form_livre_Titre(Request $request, LivresRepository $LivresRepo, EntityManagerInterface $entityManager) : Response
        {
        
            $form = $this->createForm(LivresTitreType::class, null);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $titreSelectionne = $form->get('titrelivre')->getData();
                
                
            return $this->render('livres/resultat_livre_titre.html.twig',[   // le twig ou on affiche les resultats du titre selectionner
                            
            'livres' =>$LivresRepo->findBy(['titre_livre'=> $titreSelectionne]), 
            // Titre_livre meme écris dans l'entité livres
            ]);
            }

            return $this->render('livres/livres_titre.html.twig', [
                'form' => $form->createView(),
            ]);
        }

    // livre par editeur
    //--------------------
         
    #[Route('/livres/editeur', name: 'livres_editeur', methods: ['GET','POST'])]
    public function form_livre_editeur(Request $request, LivresRepository $LivresRepo, EntityManagerInterface $entityManager) : Response
    {

    $form = $this->createForm(LivresEditeurType::class, null);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $editeurSelectionne = $form->get('editeur')->getData();
        
            
        return $this->render('livres/resultat_livre_editeur.html.twig',[   // le twig ou on affiche les resultats du titre selectionner
                        
        'livres' =>$LivresRepo->findBy(['editeur'=> $editeurSelectionne]), 
        // editeur_livre meme écris dans l'entité livres
        ]);
        }

        return $this->render('livres/livres_editeur.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}



