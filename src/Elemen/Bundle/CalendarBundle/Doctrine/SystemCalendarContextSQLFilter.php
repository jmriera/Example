<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 12/12/17
 * Time: 16:14
 */

namespace Elemen\Bundle\CalendarBundle\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Elemen\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
/** @todo Borrar esta clase */
class SystemCalendarContextSQLFilter extends SQLFilter
{

    /**
     * Token Accessor
     * @var TokenAccessorInterface
     */
    protected $token;

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        $associationMappings = $targetEntity->getAssociationMappings();
        if(array_key_exists('systemCalendar',$associationMappings) )
           return sprintf('%s.%s = %d',
               $targetTableAlias,
               $associationMappings['systemCalendar']['targetToSourceKeyColumns']['id'],
               $this->token->getSystemCalendarId());

           return '';

    }

    public function setToken($token)
    {
        $this->token = $token;
    }


}