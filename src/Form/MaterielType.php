<?php

namespace App\Form;

use App\Entity\Materiel;
use App\Entity\TVA;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom du matériel'])
            ->add('prixHT', NumberType::class, ['label' => 'Prix HT (€)', 'scale' => 2])
            ->add('tva', EntityType::class, [
                'class' => TVA::class,
                'choice_label' => 'libelle',
                'label' => 'TVA',
            ])
            ->add('prixTTC', NumberType::class, ['label' => 'Prix TTC (€)', 'scale' => 2])
            ->add('quantite', IntegerType::class, ['label' => 'Quantité'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Materiel::class]);
    }
}