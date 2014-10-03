<?php
namespace M6Web\Bundle\MonologExtraBundle\Processor;

class ContextInformationProcessor
{
    protected $configuration;

    /**
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        $record['context'] = array_merge($this->configuration, $record['context']);

        return $record;
    }

    public function setConfiguration($config)
    {
        $this->configuration = $config;
    }
}
