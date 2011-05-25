<?php

/*
 * This file is part of the FunkitonInjector bundle
 *
 * (c) Lenar Lõhmus <lenar@city.ee>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Funkiton\InjectorBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

/**
 * FunkitonInjectorExtension.
 *
 * @author Lenar Lõhmus <lenar@city.ee>
 */
class FunkitonInjectorExtension extends Extension
{
    /**
     * Loads the configuration.
     *
     * @param array            $config    An array of configuration settings
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('injector.xml');

        $container->getDefinition('funkiton_injector.injector')->replaceArgument(0, $config['definitions']);

        $this->addClassesToCompile(array(
        ));
    }

    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }

    public function getNamespace()
    {
        return 'http://symfony.com/schema/dic/funkiton_injector';
    }

}
