<?php

namespace ContainerGCDtu2W;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getSncRedis_DefaultService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'snc_redis.default' shared service.
     *
     * @return \Predis\Client
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/predis/predis/src/ClientInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/predis/predis/src/Client.php';
        include_once \dirname(__DIR__, 4).'/vendor/predis/predis/src/Connection/ParametersInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/predis/predis/src/Connection/Parameters.php';
        include_once \dirname(__DIR__, 4).'/vendor/snc/redis-bundle/src/Factory/PredisParametersFactory.php';
        include_once \dirname(__DIR__, 4).'/vendor/predis/predis/src/Configuration/OptionsInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/predis/predis/src/Configuration/Options.php';
        include_once \dirname(__DIR__, 4).'/vendor/predis/predis/src/Connection/FactoryInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/predis/predis/src/Connection/Factory.php';
        include_once \dirname(__DIR__, 4).'/vendor/snc/redis-bundle/src/Client/Predis/Connection/ConnectionFactory.php';
        include_once \dirname(__DIR__, 4).'/vendor/snc/redis-bundle/src/Logger/RedisLogger.php';

        $a = new \Snc\RedisBundle\Client\Predis\Connection\ConnectionFactory();
        $a->setStopwatch(NULL);
        $a->setConnectionWrapperClass('Snc\\RedisBundle\\Client\\Predis\\Connection\\ConnectionWrapper');
        $a->setLogger(new \Snc\RedisBundle\Logger\RedisLogger(($container->privates['logger'] ?? self::getLoggerService($container))));

        return $container->privates['snc_redis.default'] = new \Predis\Client(\Snc\RedisBundle\Factory\PredisParametersFactory::create(['commands' => [], 'read_write_timeout' => NULL, 'iterable_multibulk' => false, 'serialization' => 'default', 'prefix' => NULL, 'service' => NULL, 'async_connect' => false, 'timeout' => 5, 'persistent' => false, 'exceptions' => true, 'ssl' => [], 'logging' => true, 'alias' => 'default'], 'Predis\\Connection\\Parameters', 'redis://cache'), new \Predis\Configuration\Options(['commands' => [], 'read_write_timeout' => NULL, 'iterable_multibulk' => false, 'serialization' => 'default', 'prefix' => NULL, 'service' => NULL, 'async_connect' => false, 'timeout' => 5, 'persistent' => false, 'exceptions' => true, 'ssl' => [], 'connections' => $a]));
    }
}
