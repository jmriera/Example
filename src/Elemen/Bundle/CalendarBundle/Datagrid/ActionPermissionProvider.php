<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 12/02/18
 * Time: 08:32
 */

namespace Elemen\Bundle\CalendarBundle\Datagrid;

use Elemen\Bundle\CalendarBundle\Entity\Manager\SystemCalendarManager;
use Elemen\Bundle\CalendarBundle\Model\CalendarType;
use Oro\Bundle\DataGridBundle\Datasource\ResultRecordInterface;

class ActionPermissionProvider
{
    /** @var SystemCalendarManager */
    private $systemCalendarManager;

    /**
     * @param SystemCalendarManager $systemCalendarManager
     */
    public function __construct(SystemCalendarManager $systemCalendarManager)
    {
        $this->systemCalendarManager = $systemCalendarManager;
    }
    /**
     * @param ResultRecordInterface $record
     * @return array
     */
    public function getSwitchCalendarPermission(ResultRecordInterface $record)
    {
        if ($record->getValue('id') == $this->systemCalendarManager->getCalendarContext()->getId()) {
            return [
                'activate' => false,
                'delete' => $record->getValue('type') === CalendarType::TYPE_PLANNING ? true : false,
            ];
        }
        return [
            'activate' => true,
            'delete' => $record->getValue('type') === CalendarType::TYPE_PLANNING ? true : false,
        ];
    }
}