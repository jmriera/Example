<?php

namespace Elemen\Bundle\CalendarBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TermFirstDateLater extends Constraint
{
    public $message = "elemen.calendar.term.validation.first_day_later";


}
