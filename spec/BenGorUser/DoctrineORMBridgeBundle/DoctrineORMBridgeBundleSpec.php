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

namespace spec\BenGorUser\DoctrineORMBridgeBundle;

use BenGorUser\DoctrineORMBridgeBundle\DependencyInjection\Compiler\DoctrineORMCustomTypesPass;
use BenGorUser\DoctrineORMBridgeBundle\DependencyInjection\Compiler\DoctrineORMServicesPass;
use BenGorUser\DoctrineORMBridgeBundle\DoctrineORMBridgeBundle;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Spec file of DoctrineORMBridgeBundle class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DoctrineORMBridgeBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DoctrineORMBridgeBundle::class);
    }

    function it_extends_symfony_bundle()
    {
        $this->shouldHaveType(Bundle::class);
    }

    function it_builds(ContainerBuilder $container)
    {
        $container->getParameter('kernel.bundles')->shouldBeCalled()->willReturn([
            'BenGorUserBundle' => 'BenGorUser\\UserBundle\\BenGorUserBundle',
            'DoctrineBundle'   => 'Doctrine\\Bundle\\DoctrineBundle\\DoctrineBundle',
        ]);

        $container->addCompilerPass(
            new DoctrineORMCustomTypesPass(), PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);
        $container->addCompilerPass(
            new DoctrineORMServicesPass(), PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);

        $container->loadFromExtension('doctrine', [
            'orm' => [
                'mappings' => [
                    'DoctrineORMBridgeBundle' => [
                        'type'      => 'yml',
                        'is_bundle' => true,
                        'prefix'    => 'BenGorUser\\User\\Domain\\Model',
                    ],
                ],
            ],
        ])->shouldBeCalled()->willReturn($container);

        $this->build($container);
    }
}
