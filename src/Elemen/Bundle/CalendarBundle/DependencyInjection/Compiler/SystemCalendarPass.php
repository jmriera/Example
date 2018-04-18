<?php

namespace Elemen\Bundle\CalendarBundle\DependencyInjection\Compiler;

use Elemen\Bundle\CalendarBundle\Form\Type\SystemCalendarType;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class SystemCalendarPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('oro_calendar.system_calendar.form.type')) {
            return;
        }

        $definition = $container->getDefinition('oro_calendar.system_calendar.form.type');
        $definition->setClass(SystemCalendarType::class);
        $definition->addArgument(new Reference('oro_entity.doctrine_helper'));
    }
}
