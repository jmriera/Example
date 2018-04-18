<?php

namespace Elemen\Bundle\CalendarBundle\Provider;

use Elemen\Bundle\CalendarBundle\Entity\Term;
use Symfony\Component\Translation\TranslatorInterface;

class YearDivisionProvider
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var array
     */
    protected $translatedChoices;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public function getChoices()
    {
        if (null === $this->translatedChoices) {
            $this->translatedChoices = array();
            foreach (Term::getYearDivisions() as $name => $label) {
                $this->translatedChoices[$name] = $this->translator->trans($label);
            }
        }

        return $this->translatedChoices;
    }

    /**
     * @param string $name
     * @return string
     * @throws \LogicException
     */
    public function getLabelByName($name)
    {
        $choices = $this->getChoices();
        if (!isset($choices[$name])) {
            throw new \LogicException(sprintf('Unknown year division with name "%s"', $name));
        }

        return $choices[$name];
    }
}
