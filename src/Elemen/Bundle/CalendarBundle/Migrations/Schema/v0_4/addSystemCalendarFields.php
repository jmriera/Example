<?php

namespace Elemen\Bundle\CalendarBundle\Migrations\Schema\v0_4;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Elemen\Bundle\CalendarBundle\Model\CalendarType;
use Elemen\Bundle\CoreBundle\Migrations\Extension\ExtendExtensionAwareTrait;
use Oro\Bundle\EntityBundle\EntityConfig\DatagridScope;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\EntityExtendBundle\Migration\OroOptions;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\ParametrizedSqlMigrationQuery;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class addSystemCalendarFields implements Migration, ExtendExtensionAwareInterface
{
    use ExtendExtensionAwareTrait;
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Modify Oro System Calendar Table **/
        $this->addSystemCalendarColumns($schema, $queries);

    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function addSystemCalendarColumns(Schema $schema, QueryBag $queries)
    {
        $table = $schema->getTable('oro_system_calendar');

        $table->addColumn('start_date', 'date', [
            'oro_options' => [
                'entity'   => [
                    'label' => 'elemen.calendar.school_calendar.startDate.label',
                    'description' => 'elemen.calendar.school_calendar.startDate.description'
                ],
                'extend'    => ['is_extend' => true, 'owner' => ExtendScope::OWNER_SYSTEM ],
                'datagrid'  => ['is_visible' => DatagridScope::IS_VISIBLE_TRUE],
                'merge'     => ['display' => true],
                'dataaudit' => ['auditable' => false]
            ]
        ]);
        $table->addColumn('end_date', 'date', [
            'oro_options' => [
                'entity'   => [
                    'label' => 'elemen.calendar.school_calendar.endDate.label',
                    'description' => 'elemen.calendar.school_calendar.endDate.description'
                ],
                'extend'    => ['is_extend' => true, 'owner' => ExtendScope::OWNER_SYSTEM],
                'datagrid'  => ['is_visible' => DatagridScope::IS_VISIBLE_TRUE],
                'merge'     => ['display' => true],
                'dataaudit' => ['auditable' => false]
            ]
        ]);

        $this->addCalendarTypeField($schema, $this->extendExtension, $queries);
    }

    /**
     * Add Enum Fields Current, Planning, Historical to system_calendar table
     * @param Schema $schema
     * @param ExtendExtension $extendExtension
     * @param QueryBag $queries
     */
    public function addCalendarTypeField(Schema $schema, ExtendExtension $extendExtension, QueryBag $queries)
    {
        $enumTable = $extendExtension->addEnumField(
            $schema,
            'oro_system_calendar',
            'type',
            CalendarType::SYSTEM_CALENDAR_TYPES_CODE,
            false,
            false,
            [
                'entity'   => ['label' => 'elemen.calendar.school_calendar.type.label'],
                'extend' => ['owner' => ExtendScope::OWNER_SYSTEM],
                'datagrid' => ['is_visible' => DatagridScope::IS_VISIBLE_TRUE],
                'dataaudit' => ['auditable' => true],
                'importexport' => ["order" => 90, "short" => true]
            ]
        );

        $options = new OroOptions();
        $options->set(
            'enum',
            'immutable_codes',
            [
                'current',
                'planning',
                'historical'
            ]
        );
        /** todo agregar las opciones al enum en un dataFixture, para poder controlar la dependencia de carga de datos con el calendario de
        systema por defaul para evitar errores de que al crear el calendario no haya ningun enum existente al momento de la instalacion.
        Controlar si el error no viene por otra cosa por las dudas.
         */
        $enumTable->addOption(OroOptions::KEY, $options);
        $statuses = [
            'current'       => 'Current',
            'planning' => 'Planing',
            'historical'  => 'Historical'
        ];

        $i = 1;
        foreach ($statuses as $key => $value) {
            $dropFieldsQuery = new ParametrizedSqlMigrationQuery();
            $dropFieldsQuery->addSql(
                'INSERT INTO oro_enum_calendar_types (id, name, priority, is_default)
                          VALUES (:id, :name, :priority, :is_default)',
                ['id' => $key, 'name' => $value, 'priority' => $i, 'is_default' => 'planning' === $key],
                [
                    'id' => Type::STRING,
                    'name' => Type::STRING,
                    'priority' => Type::INTEGER,
                    'is_default' => Type::BOOLEAN
                ]
            );
            $queries->addQuery($dropFieldsQuery);
            $i++;
        }
    }
}
