<?php

namespace App\Form;

use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('marque', ChoiceType::class, [
                'choices' => [
                    'Peugeot' => 'Peugeot',
                    'BMW' => 'BMW',
                    'Audi' => 'Audi',
                    'Fiat' => 'Fiat',
                    'Ford' => 'Ford',
                    'Renault' => 'Renault',
                    'Chevrolet' => 'Chevrolet'
                ],
                'placeholder' => '--choisir--'
            ])
            ->add('modele')
            ->add('description')
            ->add('photo', FileType::class, ["mapped" => false , "required" => false])
            ->add('prixJournalier') //, MoneyType::class
            //->add('dateEnregistrement')
            ->add("save", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
