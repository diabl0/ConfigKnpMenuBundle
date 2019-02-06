<?php

/**
 * Copyright 2014 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2014 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/ConfigKnpMenuBundle/blob/master/LICENSE
 * @link https://github.com/jbouzekri/ConfigKnpMenuBundle
 */

/**
 * @namespace
 */
namespace CKMB\Bundle\ConfigKnpMenuBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ConfigKnpMenuExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        // Set configuration to be used in a custom service
        $container->setParameter('ckmb_config.menu.configuration', $config['menu']);

        // Last argument of this service is always the menu configuration
        $container
            ->getDefinition('ckmb_config.menu.provider')
            ->addArgument($config['menu']);
    }

    /**
     * Parse a navigation.yml file
     *
     * @param string $file
     *
     * @return array
     */
    public function parseFile($file)
    {
        $bundleConfig = Yaml::parse(file_get_contents(realpath($file)));

        if (!is_array($bundleConfig)) {
            return array();
        }

        return $bundleConfig;
    }
}
