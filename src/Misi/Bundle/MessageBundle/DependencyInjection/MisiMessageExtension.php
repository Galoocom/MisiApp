<?php

namespace Misi\Bundle\MessageBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Misi\Bundle\MessageBundle\MisiMessageBundle;

/**
 * MisiMessageBundle extension
 */
class MisiMessageExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
       
        $config = $processor->processConfiguration($configuration, $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $driver = $config['driver'];

        if (!in_array($driver, MisiMessageBundle::getSupportedDrivers())) {
            throw new \InvalidArgumentException(sprintf('Driver "%s" is unsupported by MisiMessageBundle.', $driver));
        }

        $loader->load(sprintf('driver/%s.xml', $driver));

        
        $container->setParameter('misi_message.driver', $driver);
        $container->setParameter('misi_message.driver.'.$driver, true);
    }
    
    /**
     * Remap class parameters.
     *
     * @param array            $classes
     * @param ContainerBuilder $container
     */
    protected function mapClassParameters(array $classes, ContainerBuilder $container)
    {
        foreach ($classes as $model => $serviceClasses) {
            foreach ($serviceClasses as $service => $class) {
                $service = $service === 'form' ? 'form.type' : $service;
                $container->setParameter(sprintf('misi.%s.%s.class', $service, $model), $class);
            }
        }
    }
}
