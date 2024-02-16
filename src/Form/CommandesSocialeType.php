<?php

namespace App\Form;

use App\Entity\Commander;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\FournisseursRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommandesSocialeType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('raisonsociale', ChoiceType::class, [
                'label' => 'Raison sociale',
                'choices' => $this->getRaisonSociale(),
                'choice_value' => $this->getRaisonSociale(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commander::class,
        ]);
    }

    private function getRaisonSociale()
    {
        // Exemple : exécuter une requête SQL
        $query = $this->entityManager->createQuery('SELECT c     
        FROM App\Entity\Commander c
        JOIN App\Entity\Fournisseurs f 
        ');
        $commandes = $query->getResult(); 


        $raisonSociale = [];
        foreach ($commandes as $commande) {
        $fournisseur = $commande->getIdFournisseur();
        $raisonSociale[$fournisseur->getRaisonSociale()] = $fournisseur->getRaisonSociale();
        }
        return $raisonSociale;
    }

    private function getIdFournisseur(){
        $query = $this->entityManager->createQuery('SELECT c     
        FROM App\Entity\Commander c
        JOIN App\Entity\Fournisseurs f 
        ');
        $commandes = $query->getResult(); 


        $idFournisseur = [];
        foreach ($commandes as $commande) {
        $fournisseur = $commande->getIdFournisseur();
        $idFournisseur[$fournisseur->getId()] = $fournisseur->getId();
        }
        return $idFournisseur;

    }

}
