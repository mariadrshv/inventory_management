<?php

namespace App\Form;

use App\Entity\Technician;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * Class TechnicianType
 * @package App\Form
 */

class TechnicianType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('phone', IntegerType::class,[
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
                ],
            ])
            ->add('email',EmailType::class)
            ->add('line1')
            ->add('line2')
            ->add('city')
            ->add('type')
            ->add('state', ChoiceType::class, [
                'choices' => Technician::STATES,
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
            ->add('type', ChoiceType::class, [
                'choices' => Technician::TYPES,
            ])
            ->add('photo', FileType::class, [
                'label'=> 'Technician photo',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypesMessage' => 'Please upload a valid photo',
                    ])
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Technician::class,
        ]);
    }
}
