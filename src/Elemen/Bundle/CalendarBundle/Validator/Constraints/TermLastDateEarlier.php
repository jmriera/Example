<?php

namespace Elemen\Bundle\CalendarBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TermLastDateEarlier extends Constraint
{
    public $message = "elemen.calendar.term.validation.first_date_earlier";



}
