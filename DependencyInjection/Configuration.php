<?php

namespace Cmobi\QRCodeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bushidoio_qrcode');

        $this->addOptions($rootNode);

        return $treeBuilder;
    }

    /**
     * Add options to the configuration tree
     *
     * @param ArrayNodeDefinition $node
     */
    private function addOptions(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->booleanNode('cacheable')
                    ->defaultTrue()
                    ->info('Use cache. More disk reads but less CPU usage. Masks and format templates are stored there.')
                ->end()
            ->end()
            ->children()
                ->scalarNode('cache_dir')
                    ->defaultValue('')
                    ->info('Cache dir')
                    ->example('/var/cache/')
                ->end()
            ->end()
            ->children()
                ->scalarNode('logs_dir')
                    ->defaultValue('')
                    ->info('Logs dir')
                    ->example('/var/logs/')
                ->end()
            ->end()
            ->children()
                ->booleanNode('find_best_mask')
                    ->defaultTrue()
                    ->info('If true, estimates best mask. Set to false to significant performance boost but (probably) worst quality code.')
                ->end()
            ->end()
            ->children()
                ->booleanNode('find_from_random')
                    ->defaultFalse()
                    ->info('If false, checks all masks available.')
                ->end()
            ->end()
            ->children()
                ->scalarNode('default_mask')
                    ->defaultValue('2')
                    ->info('Default mask when find_best_mask is false')
                    ->example('2')
                    ->validate()
                        ->ifTrue(function($v) {
                            return (0 === preg_match('/^[0-9]+$/', $v));
                        })
                        ->thenInvalid('default_mask must be an integer value greater than 0 (default value 2).')
                    ->end()
                ->end()
            ->end()
            ->children()
                ->scalarNode('png_maximum_size')
                    ->defaultValue('1024')
                    ->info('PNG image maximum size')
                    ->example('1024')
                    ->validate()
                        ->ifTrue(function($v) {
                            return (0 === preg_match('/^[0-9]+$/', $v));
                        })
                        ->thenInvalid('png_maximum_size must be an integer value greater than 0 (default value 1024).')
                    ->end()
                ->end()
            ->end()
            ->children()
                ->booleanNode('absolute_url')
                    ->defaultTrue()
                    ->info('Create absolute URLs for the images. If false URLs will be relative.')
                ->end()
            ->end()
            ->children()
                ->scalarNode('http_max_age')
                    ->defaultValue('600')
                    ->info('Max age in seconds for the HTTP cache')
                    ->example('600')
                    ->validate()
                        ->ifTrue(function($v) {
                            return (0 === preg_match('/^[0-9]+$/', $v));
                        })
                        ->thenInvalid('http_max_age must be an integer value greater than 0 (default value 600).')
                    ->end()
                ->end()
            ->end()
            ->children()
                ->scalarNode('https_max_age')
                    ->defaultValue('600')
                    ->info('Max age in seconds for the HTTP cache in shared connections (proxies)')
                    ->example('600')
                    ->validate()
                        ->ifTrue(function($v) {
                            return (0 === preg_match('/^[0-9]+$/', $v));
                        })
                        ->thenInvalid('https_max_age must be an integer value greater than 0 (default value 600).')
                    ->end()
                ->end()
            ->end()
        ;
    }
}
