<?php

namespace Sokil\LocaleBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class LocaleExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // set query parameter
        $container->setParameter(
            'locale.config.query_parameter',
            empty($config['query_parameter']) ? null : $config['query_parameter']
        );

        // set cookie parameter
        $container->setParameter(
            'locale.config.cookie_parameter',
            empty($config['cookie_parameter']) ? null : $config['cookie_parameter']
        );

        // set cookie parameter
        $container->setParameter(
            'locale.config.path_parameter',
            empty($config['path_parameter']) ? false : $config['path_parameter']
        );

        // supported locales
        if (empty($config['locales'])) {
            throw new \Exception('Supported locales not configured');
        } else {
            $container->setParameter('locale.config.locales', $config['locales']);
        }


        // load services
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
