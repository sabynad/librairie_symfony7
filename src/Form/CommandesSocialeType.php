<?php

namespace App\Form;

use App\Entity\Commander;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Repository\FournisseursRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Fournisseurs;

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
                // 'choice_label' => 'raisonsociale',
                // 'choice_value' => 'id',
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
        $fournisseurs = $this->entityManager->getRepository(Fournisseurs::class)->findAll();

        return $fournisseurs;
    }

    // private function getRaisonSociale(): array
    // {
    //     $query = $this->entityManager->createQuery('
    //         SELECT f
    //         FROM App\Entity\Fournisseurs f
    //     ');
    //     $fournisseurs = $query->getResult(); 

    //     $raisonSociale = [];
    //     foreach ($fournisseurs as $fournisseur) {
    //         $raisonSociale[$fournisseur->getRaisonSociale()] = $fournisseur->getRaisonSociale();
    //     }
    //     return $raisonSociale;
    // }
}
