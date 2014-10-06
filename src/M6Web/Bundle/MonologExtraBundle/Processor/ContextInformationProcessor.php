<?php
namespace M6Web\Bundle\MonologExtraBundle\Processor;

class ContextInformationProcessor
{
    /**
     * Processor configuration
     *
     * @var array
     */
    protected $configuration;

    /**
     * @param  array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        $record['context'] = array_merge($this->configuration, $record['context']);

        return $record;
    }

    /**
     * Define processor configuration
     *
     * @param array $config
     */
    public function setConfiguration(array $config)
    {
        $this->configuration = $config;
    }
}
