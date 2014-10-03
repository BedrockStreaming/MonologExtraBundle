<?php
namespace M6Web\Bundle\MonologExtraBundle\Tests\Units\Processor;

use mageekguy\atoum\test;

use M6Web\Bundle\MonologExtraBundle\Processor\ContextInformationProcessor as Base;

class ContextInformationProcessor extends test
{
    public function testInvoke()
    {
        $data = [ 'foo' => uniqid(), 'bar' => uniqid() ];

        $processor = new Base;
        $processor->setConfiguration($data);

        $this
            ->array($record = call_user_func($processor, ['context' => []]))
            ->string($record['context']['foo'])
                ->isEqualTo($data['foo'])
            ->string($record['context']['bar'])
                ->isEqualTo($data['bar'])
        ;
    }
}
