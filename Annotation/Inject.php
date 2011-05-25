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
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

/**
 * The @Inject annotation
 *
 * @author Lenar Lõhmus <lenar@city.ee>
 */
class Inject implements InjectInterface
{
    protected $name = null;
    protected $service = null;
    protected $injector;

    public function __construct(array $values)
    {
        foreach ($values as $key => $value) {
            if ('value' === $key) {
                $this->service = $value;
            } elseif ('name' === $key) {
                $this->name = $value;
            }
        }

        if (null === $this->service) {
            throw new ParameterNotFoundException('Inject cannot proceed without service name');
        }
        if (null === $this->name) {
            $this->name = $this->service;
        }
        $this->name = preg_replace('/[^[:alnum:]_]+/', '_', $this->name);
    }

    /**
     * {@inheritdoc}
     */
    public function inject(Injector $injector, $object)
    {
        $injector->inject($this->name, $this->service, $object);
    }
}
