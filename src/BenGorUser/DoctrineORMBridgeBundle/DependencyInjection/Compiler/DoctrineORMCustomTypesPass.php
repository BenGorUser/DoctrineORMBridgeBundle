<?php

/*
 * This file is part of the BenGorUser package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGorUser\DoctrineORMBridgeBundle\DependencyInjection\Compiler;

use BenGorUser\DoctrineORMBridge\Infrastructure\Persistence\Types\UserGuestIdType;
use BenGorUser\DoctrineORMBridge\Infrastructure\Persistence\Types\UserIdType;
use BenGorUser\DoctrineORMBridge\Infrastructure\Persistence\Types\UserRolesType;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Load Doctrine ORM custom types compiler pass.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DoctrineORMCustomTypesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('doctrine.dbal.connection_factory')) {
            return;
        }

        $customTypes = $container->getParameter('doctrine.dbal.connection_factory.types');
        $customTypes = array_merge($customTypes, [
            'user_id'       => [
                'class'     => UserIdType::class,
                'commented' => true,
            ],
            'user_guest_id' => [
                'class'     => UserGuestIdType::class,
                'commented' => true,
            ],
            'user_roles'    => [
                'class'     => UserRolesType::class,
                'commented' => true,
            ],
        ]);

        $container->setParameter('doctrine.dbal.connection_factory.types', $customTypes);
        $container->findDefinition('doctrine.dbal.connection_factory')->replaceArgument(
            0, '%doctrine.dbal.connection_factory.types%'
        );
    }
}
