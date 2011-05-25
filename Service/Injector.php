<?php

/*
 * This file is part of the FunkitonInjector bundle
 *
 * (c) Lenar Lõhmus <lenar@city.ee>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Funkiton\InjectorBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * The Injector class does the real injection
 *
 * @author Lenar Lõhmus <lenar@city.ee>
 */
class Injector
{
    /**
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var Array
     */
    protected $definitions;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container A service container
     */
    public function __construct(array $definitions, ContainerInterface $container)
    {
        $this->container = $container;
        $this->definitions = $definitions;
    }

    /**
     * Injects service into target object
     *
     * @param String $name Property name of target Object
     * @param String $service Service name to inject (either service.name or service.name::someMethod)
     * @param String $targetObject Object to retrieve the injection
     */
    public function inject($name, $service, $targetObject) {
        $value = null;
        if (null !== $service) {
            if (isset($this->definitions[$service])) {
                $service = $this->definitions[$service];
            }
            $method = null;
            if (false !== strpos($service, '::')) {
                list($service, $method) = explode('::', $service, 2);
            }

            if (!is_object($value = $this->container->get($service, true))) {
                throw new ServiceNotFoundException(sprintf('You have requested a non-existent service "%s"', $service));
            }
            if (null !== $method) {
                if (!method_exists($value, $method)) {
                    throw new RuntimeException(sprintf('Service "%s" does not have method "%s"', $service, $method));
                }
                $value = $value->$method();
                if (!is_object($value)) {
                    throw new RuntimeException(sprintf('Service %s::%s is not an object', $service, $method));
                }
            }
        }
        if (property_exists($class = get_class($targetObject), $name)) {
            $property = new \ReflectionProperty($class, $name);
            $property->setAccessible(true);
            $property->setValue($targetObject, $value);
        } else {
           $targetObject->$name = $value;
        }
    }
}
