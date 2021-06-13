<?php
namespace M6Web\Bundle\MonologExtraBundle\Tests\Units\Processor;

use atoum;
use M6Web\Bundle\MonologExtraBundle\Processor\ContextInformationProcessor as Base;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ContextInformationProcessor extends atoum
{
    public function testInvoke()
    {
        $data = [ 'foo' => uniqid(), 'bar' => uniqid(), 'expr' => 'expr(container.getParameter("test"))' ];

        $container = new ContainerBuilder();
        $container->setParameter('test', 'yes');

        $processor = new Base($container, new ExpressionLanguage());
        $processor->setConfiguration($data);

        $this
            ->array($record = call_user_func($processor, ['context' => []]))
            ->string($record['context']['foo'])
                ->isEqualTo($data['foo'])
            ->string($record['context']['bar'])
                ->isEqualTo($data['bar'])
            ->string($record['context']['expr'])
                ->isEqualTo('yes')
        ;
    }
}
