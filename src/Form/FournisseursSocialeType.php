<?php

namespace App\Form;

use App\Entity\Fournisseurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\FournisseursRepository;
use Doctrine\ORM\EntityManagerInterface;

class FournisseursSocialeType extends AbstractType
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fournisseurs::class,
        ]);
    }

    private function getRaisonSociale()
    {
        

        // Exemple : exécuter une requête DQL
        $query = $this->entityManager->createQuery('SELECT fournisseurs FROM App\Entity\Fournisseurs fournisseurs GROUP BY fournisseurs.Raison_sociale');
        $fournisseurs = $query->getResult();


        $raisonSociale = [];
        foreach ($fournisseurs as $fournisseurs) {
        
         $raisonSociale[$fournisseurs->getRaisonSociale()] = $fournisseurs->getRaisonSociale();
        }
        return $raisonSociale;
    }

}
