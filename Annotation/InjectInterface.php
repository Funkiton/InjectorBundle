<?php

/*
 * This file is part of the FunkitonInjector bundle
 *
 * (c) Lenar Lõhmus <lenar@city.ee>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Funkiton\InjectorBundle\Annotation;

use Funkiton\InjectorBundle\Service\Injector;

/**
 * InjectInterface.
 *
 * @author Lenar Lõhmus <lenar@city.ee>
 */
interface InjectInterface
{
    /**
     * Injects into target object using injector.
     *
     * @param Injector $injector An injector service
     * @param object $targetObject The target object receiving the injection
     */
    function inject(Injector $injector, $targetObject);
    
    function getName();

    function setName($name);
    
    function getService();
}
