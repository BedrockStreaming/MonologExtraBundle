<?php

namespace M6Web\Bundle\MonologExtraBundle\Tests\Units\DependencyInjection;

use M6Web\Bundle\MonologExtraBundle\DependencyInjection\M6WebMonologExtraExtension as TestedClass;

use mageekguy\atoum\test;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class M6WebMonologExtraExtension
 */
class M6WebMonologExtraExtension extends test
{
    public function testLoad()
    {
        $extension = new TestedClass();
        $container = new ContainerBuilder();
        $config = array(
            'processors' => array(
                'myProcessor' => array(
                    'type' => 'ContextInformation',
                    'handler' => 'gelf',
                    'config' => array(
                        'foo' => 'bar',
                        'bar' => 'foo',
                        'env' => "expr(container.getParameter('kernel.environment'))",
                    ),
                ),
            ),
        );

        $extension->load([$config], $container);

        $this->object($definition = $container->getDefinition('m6_web_monolog_extra.processor.myProcessor'))
            ->string($definition->getClass())
                ->isEqualTo('%m6_web_monolog_extra.processor.contextInformation.class%')
            ->boolean($definition->isAbstract())
                ->isEqualTo(false)
            ->array(array_values($definition->getMethodCalls()))
                ->isEqualTo(array(
                    array(
                        'setConfiguration',
                        array(
                            array(
                                'foo' => 'bar',
                                'bar' => 'foo',
                                'env' => "expr(container.getParameter('kernel.environment'))",
                            )
                        )
                    )
                ))
            ->array($definition->getTags())
                ->isEqualTo(array(
                    'monolog.processor' => array(
                        array('handler' => 'gelf'),
                    )
                ))
        ;
    }
}
