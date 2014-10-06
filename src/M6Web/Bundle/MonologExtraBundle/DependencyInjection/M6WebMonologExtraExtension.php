<?php

namespace M6Web\Bundle\MonologExtraBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 */
class M6WebMonologExtraExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('processors.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (!empty($config['processors'])) {
            $alias = $this->getAlias();

            foreach ($config['processors'] as $name => $processor) {
                $serviceId      = sprintf('%s.processor.%s', $alias, is_int($name) ? uniqid() : $name);
                $classParameter = sprintf('%s.processor.%s.class', $alias, $processor['type']);

                if (!$container->hasParameter($classParameter)) {
                    throw new InvalidConfigurationException(sprintf('Invalid processor type "%s"', $processor['type']));
                }

                $tagOptions = [];
                if (array_key_exists('channel', $processor)) {
                    $tagOptions['channel'] = $processor['channel'];
                }
                if (array_key_exists('handler', $processor)) {
                    $tagOptions['handler'] = $processor['handler'];
                }

                $definition = new Definition('%'.$classParameter.'%');
                $definition->addtag('monolog.processor', $tagOptions);

                if (array_key_exists('config', $processor)) {
                    $reflextion = new \ReflectionClass($container->getParameter($classParameter));
                    if ($reflextion->hasMethod('setConfiguration')) {
                        $definition->addMethodCall('setConfiguration', [$processor['config']]);
                    } else {
                        throw new InvalidConfigurationException(
                            sprintf('"%s" processor is not configurable.', $processor['type'])
                        );
                    }
                }

                $container->setDefinition($serviceId, $definition);
            }
        }
    }
}
