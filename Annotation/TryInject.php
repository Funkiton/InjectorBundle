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
use Symfony\Component\DependencyInjection\Exception\ExceptionInterface;

/**
 * The @TryInject annotation
 *
 * @author Lenar Lõhmus <lenar@city.ee>
 *
 * @Annotation
 */
class TryInject extends Inject
{
    /**
     * {@inheritdoc}
     */
    public function inject(Injector $injector, $object)
    {
        try {
            parent::inject($injector, $object);
        } catch (ExceptionInterface $e) {
            $injector->inject($this->name, null, $object);
        }
    }
}
