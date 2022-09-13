<?php
namespace M6Web\Bundle\MonologExtraBundle\Processor;

use Monolog\LogRecord;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ContextInformationProcessor
{
    protected $container;
    protected $expressionLanguage;

    public function __construct(ContainerInterface $container, ExpressionLanguage $expressionLanguage)
    {
        $this->container = $container;
        $this->expressionLanguage = $expressionLanguage;
    }

    /**
     * Processor configuration
     *
     * @var array
     */
    protected $configuration;

    public function __invoke(LogRecord $record): LogRecord
    {
        return $record->with(
            context: array_merge($this->evaluateConfiguration(), $record['context'])
        );
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

    /**
     * Evaluate configuration array
     *
     * @return array
     */
    protected function evaluateConfiguration()
    {
        $context = [];
        foreach ($this->configuration as $key => $value) {
            $context[$key] = $this->evaluateValue($value);
        }

        return $context;
    }

    /**
     * Evaluate configuration value
     *
     * @param string $value
     *
     * @return string
     */
    protected function evaluateValue($value)
    {
        if (preg_match('/^expr\((.*)\)$/', $value, $matches)) {
            return $this->expressionLanguage->evaluate($matches[1], ['container' => $this->container]);
        }

        return $value;
    }
}
