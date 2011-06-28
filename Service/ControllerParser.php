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

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Funkiton\InjectorBundle\Annotation\InjectInterface;

/**
 * The ControllerParser class parses annotation blocks located in
 * controller classes.
 *
 * @author Lenar Lõhmus <lenar@city.ee>
 */
class ControllerParser
{
    /**
     * @var Doctrine\Common\Annotations\Reader
     */
    protected $reader;

   /**
     * @var Funkiton\InjectorBundle\Service\Injector
     */
    protected $injector;

    /**
     * Constructor.
     *
     * @param AnnotationReader $reader An AnnotationReader instance
     */
    public function __construct(Injector $injector, Reader $reader)
    {
        $this->injector = $injector;
        $this->reader = $reader;
    }

    /**
     * Injects objects into controller based on class annotations
     *
     * @param FilterControllerEvent $event A FilterControllerEvent instance
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $class = new \ReflectionClass($controller[0]);

        $configs = array();
        while (false !== $class) {
            foreach ($class->getProperties() as $property) {
                foreach ($this->reader->getPropertyAnnotations($property) as $configuration) {
                    if ($configuration instanceof InjectInterface && !isset($configs[$configuration->getName()])) {
                        $configuration->setName($property->getName());
                        $configs[$configuration->getName()] = $configuration;
                    }
                    
                }
            }
            foreach ($this->reader->getClassAnnotations($class) as $configuration) {
                if ($configuration instanceof InjectInterface && !isset($configs[$configuration->getName()])) {
                    $configs[$configuration->getName()] = $configuration;
                }
            }
            $class = $class->getParentClass();
        }
        foreach ($configs as $configuration) {
            $configuration->inject($this->injector, $controller[0]);
        }
    }
}
