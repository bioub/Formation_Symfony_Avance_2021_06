<?php


namespace Romain\MyCommandBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class RomainMyCommandExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
//        $totoServices = $container->findTaggedServiceIds('toto');
//
//        foreach ($totoServices as $totoService) {
//            $totoService->toto();
//        }

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../config')
        );
        $loader->load('services.yaml');
    }
}