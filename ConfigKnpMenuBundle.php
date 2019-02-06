<?php

/**
 * @copyright Copyright 2014 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/diabl0/ConfigKnpMenuBundle/blob/master/LICENSE
 * @link https://github.com/diabl0/ConfigKnpMenuBundle
 */

/**
 * @namespace
 */
namespace CKMB\Bundle\ConfigKnpMenuBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Bundle definition
 *
 */
class ConfigKnpMenuBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}
