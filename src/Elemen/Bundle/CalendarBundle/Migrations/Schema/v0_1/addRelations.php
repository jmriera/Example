<?php

namespace Elemen\Bundle\CalendarBundle\Migrations\Schema\v0_1;

use Doctrine\DBAL\Schema\Schema;
use Elemen\Bundle\CoreBundle\Migrations\Extension\ExtendExtensionAwareTrait;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class addRelations implements Migration, ExtendExtensionAwareInterface
{
    use ExtendExtensionAwareTrait;
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->addPeriodRelation($schema);
        $this->addCycleDayRelation($schema);
    }

    public function addPeriodRelation($schema)
    {
        $this->extendExtension->addManyToOneRelation(
            $schema,
            'elemen_calendar_period',
            'systemCalendar',
            'oro_system_calendar',
            'id',
            [
                'extend' => [
                    'is_extend' => true,
                    'without_default' => true,
                    'on_delete' => 'CASCADE',
                    'nullable' => false,
                    'owner' => ExtendScope::OWNER_CUSTOM
                ],
                'form' => ['is_enabled' => false],
                'view' => ['is_displayable' => false]
            ]

        );
        $this->extendExtension->addManyToOneInverseRelation(
            $schema,
            'elemen_calendar_period',
            'systemCalendar',
            'oro_system_calendar',
            'periods',
            ['name'],
            ['name'],
            ['name'],
            [
                'extend' => [
                    'is_extend' => true,
                    'without_default' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM ,
                    'on_delete' => 'CASCADE'
                ],
                'form' => ['is_enabled' => false],
                'view' => ['is_displayable' => false]
            ]

        );

    }
    public function addCycleDayRelation($schema)
    {
        $this->extendExtension->addManyToOneRelation(
            $schema,
            'elemen_calendar_cycle_day',
            'systemCalendar',
            'oro_system_calendar',
            'id',
            [
                'extend' => [
                    'is_extend' => true,
                    'without_default' => true,
                    'on_delete' => 'CASCADE',
                    'nullable' => false,
                    'owner' => ExtendScope::OWNER_CUSTOM
                ],
                'form' => ['is_enabled' => false],
                'view' => ['is_displayable' => false]
            ]

        );
        $this->extendExtension->addManyToOneInverseRelation(
            $schema,
            'elemen_calendar_cycle_day',
            'systemCalendar',
            'oro_system_calendar',
            'cycleDays',
            ['name'],
            ['name'],
            ['name'],
            [
                'extend' => [
                    'is_extend' => true,
                    'without_default' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM ,
                    'on_delete' => 'CASCADE'
                ],
                'form' => ['is_enabled' => false],
                'view' => ['is_displayable' => false]
            ]

        );

    }

}
