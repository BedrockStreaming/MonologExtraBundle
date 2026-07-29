<?php

namespace M6Web\Bundle\MonologExtraBundle\Tests\Units\DependencyInjection;

use M6Web\Bundle\MonologExtraBundle\DependencyInjection\M6WebMonologExtraExtension as TestedClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class M6WebMonologExtraExtension.
 */
class M6WebMonologExtraExtension extends \atoum
{
    public function testLoad(): void
    {
        $extension = new TestedClass();
        $container = new ContainerBuilder();
        $config = [
            'processors' => [
                'myProcessor' => [
                    'type' => 'ContextInformation',
                    'handler' => 'gelf',
                    'config' => [
                        'foo' => 'bar',
                        'bar' => 'foo',
                        'env' => "expr(container.getParameter('kernel.environment'))",
                    ],
                ],
            ],
        ];

        $extension->load([$config], $container);

        $this->object($definition = $container->getDefinition('m6_web_monolog_extra.processor.myProcessor'))
            ->string($definition->getClass())
                ->isEqualTo('%m6_web_monolog_extra.processor.contextInformation.class%')
            ->boolean($definition->isAbstract())
                ->isEqualTo(false)
            ->array(array_values($definition->getMethodCalls()))
                ->isEqualTo([
                    [
                        'setConfiguration',
                        [
                            [
                                'foo' => 'bar',
                                'bar' => 'foo',
                                'env' => "expr(container.getParameter('kernel.environment'))",
                            ],
                        ],
                    ],
                ])
            ->array($definition->getTags())
                ->isEqualTo([
                    'monolog.processor' => [
                        ['handler' => 'gelf'],
                    ],
                ])
        ;
    }
}
