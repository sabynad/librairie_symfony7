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

class FournisseursPaysType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pays', ChoiceType::class, [
                'label' => 'Pays',
                'choices' => $this->getPays(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fournisseurs::class,
        ]);
    }

    private function getPays()
    {
        

        // Exemple : exécuter une requête DQL
        $query = $this->entityManager->createQuery('SELECT fournisseurs FROM App\Entity\Fournisseurs fournisseurs GROUP BY fournisseurs.Pays');
        $fournisseurs = $query->getResult();


        $pays = [];
        foreach ($fournisseurs as $fournisseurs) {
        
         $pays[$fournisseurs->getPays()] = $fournisseurs->getPays();
        }
        return $pays;
    }

}
