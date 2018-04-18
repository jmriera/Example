<?php

namespace Elemen\Bundle\CalendarBundle\Validator\Constraints;

use Oro\Bundle\CalendarBundle\Entity\SystemCalendar;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TermLastDateEarlierValidator extends ConstraintValidator
{
    /**
     * @param \DateTime $value
     * @param Constraint|TermLastDateEarlier $constraint
     *
     * @throws UnexpectedTypeException
     */
    public function validate($value, Constraint $constraint)
    {
        $root = $this->context->getRoot();

        if ($root instanceof FormInterface) {
            /** @var SystemCalendar $valueCompare */
            $valueCompare = $root->get('systemCalendar')->getData();
        } else {
            throw new UnexpectedTypeException($value, 'FormInterface');
        }

        // values presence should be validated by NotNullValidator
        if (!$value || !$valueCompare) {
            return;
        }

        if (!$value instanceof \DateTime) {
            throw new UnexpectedTypeException($value, 'DateTime');
        }

        if (!$valueCompare instanceof SystemCalendar) {
            throw new UnexpectedTypeException($valueCompare, 'systemCalendar');
        }

        if ($value->getTimestamp() > $valueCompare->getEndDate()->getTimestamp()) {
            $this->context->addViolation($constraint->message, ['{{lastDay}}' => $valueCompare->getEndDate()->format('d/m/Y') ]);
        }
    }
}
