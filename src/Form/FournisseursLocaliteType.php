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

class FournisseursLocaliteType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('localite', ChoiceType::class, [
                'label' => 'Localite',
                'choices' => $this->getLocalite(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fournisseurs::class,
        ]);
    }

    private function getLocalite()
    {
        

        // Exemple : exécuter une requête DQL
        $query = $this->entityManager->createQuery('SELECT fournisseurs FROM App\Entity\Fournisseurs fournisseurs GROUP BY fournisseurs.Localite');
        $fournisseurs = $query->getResult();


        $localite = [];
        foreach ($fournisseurs as $fournisseurs) {
        
         $localite[$fournisseurs->getLocalite()] = $fournisseurs->getLocalite();
        }
        return $localite;
    }

}
