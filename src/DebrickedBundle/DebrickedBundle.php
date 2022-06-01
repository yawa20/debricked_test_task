<?php

declare(strict_types=1);

namespace DebrickedBundle;

use DebrickedBundle\Debricked\Api\Client\ApiClientInterface;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class DebrickedBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
            ->scalarNode('long_file_token')->defaultValue('%env(DEBRICKED_API_TOKEN)%')->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {

        $container->import('./config/services.yaml');
        $container->services()
            ->get(ApiClientInterface::class)
            ->arg('$token', $config['long_file_token']);
    }
}