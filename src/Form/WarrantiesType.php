<?php

namespace App\Form;

use App\Entity\Warranties;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class WarrantiesType
 * @package App\Form
 */
class WarrantiesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('provider_name')
            ->add('exp_date')
            ->add('line1')
            ->add('line2')
            ->add('city')
            ->add('state', ChoiceType::class, [
                'choices' => Warranties::STATES,
            ])
            ->add('zip', IntegerType::class, [
                'label' => 'Zip code',
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Your zip code must include min 5 values and max 16 values, only digits',
                        'max' => 16,
                    ])
                ]
            ])
            ->add('phone_number', IntegerType::class,[
                'label' => 'Telephone number (full, please, like: 375291112233) ',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a phone number',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Your phone number should be at least {{ limit }} numeric',
                        'max' => 13,
                    ]),
                ],])
            ->add('notes')
            ->add('photo', FileType::class, [
                'label'=> 'Photo of property',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypesMessage' => 'Please upload a valid photo',
                    ])
                ]
            ]);
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Warranties::class,
        ]);
    }
}
