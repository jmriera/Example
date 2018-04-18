<?php

namespace Elemen\Bundle\CalendarBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SystemCalendarSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'autocomplete_alias' => 'system_calendar',
                'configs'            => [
                    'placeholder' => 'elemen.calendar.term.form.choose_calendar.placeholder'
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'oro_entity_select';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'elemen_systemcalendar_select';
    }
}
