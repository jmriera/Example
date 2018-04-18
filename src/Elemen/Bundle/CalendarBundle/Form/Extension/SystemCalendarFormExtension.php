<?php

namespace Elemen\Bundle\CalendarBundle\Form\Extension;

use Elemen\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

use Oro\Bundle\FormBundle\Form\Extension\Traits\FormExtendedTypeTrait;

class SystemCalendarFormExtension extends AbstractTypeExtension
{
    use FormExtendedTypeTrait;
    
    /** @var TokenAccessorInterface */
    protected $tokenAccessor;

    /** @var PropertyAccessor */
    protected $propertyAccessor;

    /**
     * @param TokenAccessorInterface $tokenAccessor
     */
    public function __construct(TokenAccessorInterface $tokenAccessor) {
        $this->tokenAccessor = $tokenAccessor;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // listener must be executed before validation
        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit'], 128);
    }

    /**
     * @param FormEvent $event
     * @throws \Throwable
     * @throws \TypeError
     */
    public function onPostSubmit(FormEvent $event)
    {
        $data = $event->getForm()->getData();

        if (is_array($data) || $data instanceof \Traversable) {
            foreach ($data as $value) {
                if (is_object($value)) {
                    $this->updateSystemCalendar($value);
                }
            }
        } elseif (is_object($data)) {
            $this->updateSystemCalendar($data);
        }
    }

    /**
     * @param object $entity
     * @throws \Throwable
     * @throws \TypeError
     */
    protected function updateSystemCalendar($entity)
    {
        if (!$this->getPropertyAccessor()->isReadable($entity, 'systemCalendar')) {
            return;
        }

        $systemCalendar = $this->getPropertyAccessor()->getValue($entity, 'systemCalendar');
        if ($systemCalendar) {
            return;
        }

        $systemCalendar = $this->tokenAccessor->getSystemCalendar();
        if (null === $systemCalendar) {
            return;
        }

        $this->getPropertyAccessor()->setValue($entity, 'systemCalendar', $systemCalendar);
    }

    /**
     * @return PropertyAccessor
     */
    protected function getPropertyAccessor()
    {
        if (!$this->propertyAccessor) {
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        return $this->propertyAccessor;
    }
}
