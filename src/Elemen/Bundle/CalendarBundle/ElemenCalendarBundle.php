<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 17/10/17
 * Time: 16:30
 */

namespace Elemen\Bundle\CalendarBundle;

use Elemen\Bundle\CalendarBundle\DependencyInjection\Compiler\SystemCalendarPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ElemenCalendarBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new SystemCalendarPass());
    }

    public function getParent()
    {
        return 'OroCalendarBundle';
    }


}