<?php

namespace Elemen\Bundle\CalendarBundle\EventListener;

use Doctrine\ORM\Query\AST\IdentificationVariableDeclaration;
use DotMailer\Api\Rest\NotFoundException;
use Elemen\Bundle\CalendarBundle\Entity\Manager\SystemCalendarManager;
use Elemen\Bundle\CalendarBundle\Entity\SystemCalendarAwareInterface;
use Oro\Bundle\SecurityBundle\Event\ProcessSelectAfter;
use Oro\Bundle\SecurityBundle\ORM\Walker\Condition\AclCondition;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class SystemCalendarFilterSubscriber
{
    /**
     * @var SystemCalendarManager
     */
    protected $systemCalendarManager;

    /**
     * @param SystemCalendarManager $systemCalendarManager
     */
    public function __construct(SystemCalendarManager $systemCalendarManager)
    {
        $this->systemCalendarManager = $systemCalendarManager;
    }

    /**
     * @param ProcessSelectAfter $event
     */
    public function onSelectAfter(ProcessSelectAfter $event)
    {
        $calendarContext = $this->systemCalendarManager->getCalendarContext();

        $array = $event->getSelect()->fromClause->identificationVariableDeclarations;

        /** @var IdentificationVariableDeclaration $identificationVariableDeclaration */
        foreach ($array as $identificationVariableDeclaration) {
            $entityAlias = $identificationVariableDeclaration->rangeVariableDeclaration->aliasIdentificationVariable;
            $class = $identificationVariableDeclaration->rangeVariableDeclaration->abstractSchemaName;
            $isRoot = $identificationVariableDeclaration->rangeVariableDeclaration->isRoot;
            if ($isRoot) {
                if(is_subclass_of($class, SystemCalendarAwareInterface::class)){
                    $whereConditions = $event->getWhereConditions();
                    $whereConditions[] = new AclCondition(
                        $entityAlias,
                        null,
                        null,
                        4,
                        'systemCalendar',
                        $calendarContext->getId(),
                        true
                    );
                    $event->setWhereConditions($whereConditions);

                }
            }
        }


    }
}
