<?php
namespace M6Web\Bundle\MonologExtraBundle\Tests\Units\Processor;

use atoum;
use M6Web\Bundle\MonologExtraBundle\Processor\ContextInformationProcessor as Base;
use Monolog\Level;
use Monolog\LogRecord;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ContextInformationProcessor extends atoum
{
    public function testInvoke(): void
    {
        $data = [ 'foo' => uniqid(), 'bar' => uniqid(), 'expr' => 'expr(container.getParameter("test"))' ];

        $container = new ContainerBuilder();
        $container->setParameter('test', 'yes');

        $processor = new Base($container, new ExpressionLanguage());
        $processor->setConfiguration($data);

        $record = call_user_func($processor, new LogRecord(
            datetime: new \DateTimeImmutable(),
            channel: uniqid('channel_', true),
            level: Level::Info,
            message: uniqid('message_', true),
            context: []
        ));

        $this
            ->object($record)
            ->string($record['context']['foo'])
                ->isEqualTo($data['foo'])
            ->string($record['context']['bar'])
                ->isEqualTo($data['bar'])
            ->string($record['context']['expr'])
                ->isEqualTo('yes')
        ;
    }
}
