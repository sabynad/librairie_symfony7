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
use App\Form\CommandesSocialeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


#[Route('/commandes')]
class CommandesController extends AbstractController
{
    //afficher tte les commandes
    //---------------------------
    #[Route('/', name: 'app_commandes',methods:['GET'])]
    public function index(CommanderRepository $commanderRepository): Response
    {
        return $this->render('commandes/all_commandes.html.twig', [
            'commandes' =>$commanderRepository->findAllCommandesWithJointures(),
        ]);
    }


    // Ajout commande
    //________________

    #[Route('/ajout', name: 'ajout_commande', methods:['GET', 'POST'])]
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


    //Update commande
    //________________
    #[Route('/{id}/update', name: 'update_commande',methods:['GET','POST'])]
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
    //_________________
    #[Route('/{id}/delete', name: 'delete_commande')]
    public function delete_commande( int $id, EntityManagerInterface $entityManager,  CommanderRepository $commanderRepository): Response
    {
        $commande = $commanderRepository->find($id);  //récupère le livre à partir de l'Id
        // var_dump($commande);
        $entityManager->remove($commande);

        $entityManager->flush();
        return $this->redirectToRoute('app_commandes');
    }


    //commandes par raison sociale
    //--------------------------------NE MARCHE PAS----------------------
    // #[Route('/raisonsociale', name: 'commandes_raison_sociale', methods: ['GET','POST'])]
    // public function commandes_raison_sociale(Request $request,CommanderRepository $CommandesRepo, EntityManagerInterface $entityManager) : Response
    // {
    // $commandesJointures = $CommandesRepo->findAllCommandesWithJointures();
    // $form = $this->createForm(CommandesSocialeType::class, null);

    // $form->handleRequest($request);

    // if ($form->isSubmitted() && $form->isValid()) {
    //     $raisonSocialeSelectionne = $form->get('raisonsociale')->getData(); // raisonsociale même nom que dans le formulaire builder
        
    //     $raisonSocialeChoisie = $raisonSocialeSelectionne->getId();
            
    //     return $this->render('commandes/raison_sociale_resultat.html.twig',[   // le twig ou on affiche les resultats du titre selectionner
                        
    //     'commandes' => $CommandesRepo->findBy(['Id_fournisseur' => $raisonSocialeChoisie]), 
    //     'formdata' => $raisonSocialeSelectionne,
    //     // Raison_sociale meme écris dans l'entité fournsiseurs
    //     ]);
    //     }

    //     return $this->render('commandes/raison_sociale.html.twig', [
    //         'form' => $form->createView(),
    //     ]);

    #[Route('/raisonsociale', name: 'commandes_raison_sociale', methods: ['GET', 'POST'])]
        public function commandes_raison_sociale(Request $request, CommanderRepository $commanderRepository, EntityManagerInterface $entityManager): Response
        {
            $commandes = $commanderRepository->findAllCommandesWithJointures();
            $form = $this->createFormBuilder()
                    ->add('Fournisseur', ChoiceType::class, [
                        'choices' => $commandes, 
                        'choice_label' => 'Idfournisseur.raisonsociale',
                        'choice_value' => 'Idfournisseur.id',
                        'placeholder' => 'Choisir un fournisseur', 
                        'required' => false, 
                    ])
                    ->getForm();
            $form->handleRequest($request);
                    
            if ($form->isSubmitted() && $form->isValid()) {
                $commandeSelectionne = $form->get('Fournisseur')->getData();
                $commandeSelectionneFourni = $commandeSelectionne->getIdFournisseur();
                return $this->render('commandes/raison_sociale_resultat.html.twig', [
                    'commandes' => $commanderRepository->findBy(['Id_fournisseur' => $commandeSelectionneFourni]),
                ]);
            }
            return $this->render('commandes/raison_sociale.html.twig', [
                'form' => $form->createView(),
                'sujetRecherche' => 'fournisseur',
            ]);
        }


    //commande par date (methode thierry sans créer un formulaire)
    //_______________________________________________________________

    #[Route('/pardate', name: 'commandes_date', methods: ['GET', 'POST'])]
    public function commandes_date(Request $request, CommanderRepository $commanderRepository, EntityManagerInterface $entityManager): Response
    {
        $commandes = $commanderRepository->findAll();

        $choices=[];

        foreach ($commandes as $commande) {
            $dateAchat = $commande->getDateAchat();
            $choices[$dateAchat->format('d-m-Y')] =$dateAchat;  
        }

        $form = $this->createFormBuilder()
                ->add('commande', ChoiceType::class, [
                    'choices' => $choices, 
                    // 'choice_label' => 'id_commande.Date_achat',
                    // 'choice_value' => 'id_commande.id',
                    'placeholder' => 'Choisir une date', 
                    'required' => false, 
                ])
                ->getForm();
        $form->handleRequest($request);
                
        if ($form->isSubmitted() && $form->isValid()) {
            $commandeSelectionne = $form->get('commande')->getData();
            // $commandeSelectionneDate = new \DateTimeImmutable ($commandeSelectionne);
            return $this->render('commandes/date_resultat.html.twig', [
                'commandes' => $commanderRepository->findBy(['Date_achat' => $commandeSelectionne]),
            ]);
        }
        return $this->render('commandes/date.html.twig', [
            'form' => $form->createView(),
            'sujetRecherche' => 'date',
        ]);
    
    } 

    //commande par editeur (methode thierry sans créer un formulaire)
    //_______________________________________________________________

    #[Route('/editeur', name: 'commandes_editeur', methods: ['GET', 'POST'])]
    public function commandes_editeur(Request $request, CommanderRepository $commanderRepository, EntityManagerInterface $entityManager): Response
    {
        $commandes = $commanderRepository->findAllCommandesWithJointures();
        $form = $this->createFormBuilder()
                ->add('Livre', ChoiceType::class, [
                    'choices' => $commandes, 
                    'choice_label' => 'IdLivre.editeur',
                    'choice_value' => 'IdLivre.id',
                    'placeholder' => 'Choisir un editeur', 
                    'required' => false, 
                ])
                ->getForm();
        $form->handleRequest($request);
                
        if ($form->isSubmitted() && $form->isValid()) {
            $commandeSelectionne = $form->get('Livre')->getData();
            $commandeSelectionneLivre = $commandeSelectionne->getIdLivre();
            return $this->render('commandes/editeur_resultat.html.twig', [
                'commandes' => $commanderRepository->findBy(['Id_Livre' => $commandeSelectionneLivre]),
            ]);
        }
        return $this->render('commandes/editeur.html.twig', [
            'form' => $form->createView(),
            'sujetRecherche' => 'Livre',
        ]);
    }


}
