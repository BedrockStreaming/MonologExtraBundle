<?php

namespace M6Web\Bundle\MonologExtraBundle\Processor;

use Monolog\LogRecord;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ContextInformationProcessor
{
    protected ContainerInterface $container;
    protected ExpressionLanguage $expressionLanguage;

    public function __construct(ContainerInterface $container, ExpressionLanguage $expressionLanguage)
    {
        $this->container = $container;
        $this->expressionLanguage = $expressionLanguage;
    }

    /** @var array<string, string> */
    protected array $configuration;

    public function __invoke(LogRecord $record): LogRecord
    {
        return $record->with(
            context: array_merge($this->evaluateConfiguration(), $record->context)
        );
    }

    /** @param array<string, string> $config */
    public function setConfiguration(array $config): void
    {
        $this->configuration = $config;
    }

    /** @return array<string, string> */
    protected function evaluateConfiguration(): array
    {
        return array_map(fn ($value) => $this->evaluateValue($value), $this->configuration);
    }

    /**
     * Evaluate configuration value.
     */
    protected function evaluateValue(string $value): string
    {
        if (preg_match('/^expr\((.*)\)$/', $value, $matches)) {
            $result = $this->expressionLanguage->evaluate($matches[1], ['container' => $this->container]);
            if (!is_string($result)) {
                throw new \UnexpectedValueException(\sprintf('Expression "%s" must evaluate to a string, got %s.', $matches[1], get_debug_type($result)));
            }

            return $result;
        }

        return $value;
    }
}
