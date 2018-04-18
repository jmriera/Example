<?php

namespace Elemen\Bundle\CalendarBundle\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CycleDayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('name', 'text', [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('shortName', 'text', [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 3])
                ]
            ])
            ->add('priority', 'hidden', ['empty_data' => 9999]);

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['allow_delete'] = true;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Elemen\Bundle\CalendarBundle\Entity\CycleDay',
            'allow_multiple_selection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'elemen_cycle_day';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
