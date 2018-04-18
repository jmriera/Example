<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 14/02/18
 * Time: 08:28
 */

namespace Elemen\Bundle\CalendarBundle\Acl\Voter;

use Elemen\Bundle\CalendarBundle\Entity\SystemCalendarAwareInterface;
use Elemen\Bundle\SecurityBundle\Authentication\Token\SystemCalendarContextTokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SystemCalendarPermissionVoter extends Voter
{
    const ATTRIBUTE_VIEW   = 'VIEW';

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        return ($subject instanceof  SystemCalendarAwareInterface) && (self::ATTRIBUTE_VIEW === $attribute);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute,  $subject, TokenInterface $token)
    {
        if (!$token instanceof SystemCalendarContextTokenInterface) {
            return $this::ACCESS_DENIED;
        }

        /* @var $subject SystemCalendarAwareInterface */
        return $token->getCalendarContext()->getId() == $subject->getSystemCalendar()->getId();
    }

}