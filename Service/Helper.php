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
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * The Helper class provies useful shortcuts to services and their methods.
 *
 * @author Lenar Lõhmus <lenar@city.ee>
 */
class Helper
{
    /**
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container A service container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSecurityContext()
    {
        return $this->container->get('security.context');
    }

    public function getRequest()
    {
        return $this->container->get('request');
    }

    public function getSession()
    {
        return $this->getRequest()->getSession();
    }

    public function getToken()
    {
        return $this->getSecurityContext()->getToken();
    }

    public function getUser()
    {
        $user = $this->getToken()->getUser();
        if (!is_object($user))
        {
            throw new RuntimeException('User is not available');
        }
        return $user;
    }

    public function getEntityManager()
    {
        return $this->container->get('doctrine')->getEntityManager();
    }
}
