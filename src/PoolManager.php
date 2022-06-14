<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\ISPO;

use CardanoPress\Traits\Instantiable;
use CardanoPress\Traits\Loggable;
use PBWebDev\CardanoPress\Blockfrost;
use Psr\Log\LoggerInterface;
use ThemePlate\Cache\CacheManager;
use ThemePlate\Process\Tasks;
use WP_Error;

class PoolManager
{
    use Instantiable;
    use Loggable;

    protected Tasks $tasks;
    protected CacheManager $cache;

    public const IDENTIFIER = 'cp-ispo';
    public const EXPIRATION = 15; // in minutes

    public function __construct(LoggerInterface $logger)
    {
        $this->setInstance($this);
        $this->setLogger($logger);

        $tasks = new Tasks(self::IDENTIFIER);
        $this->cache = new CacheManager($tasks);

        $tasks->report([$this->getLogger(), 'info']);
    }

    public function getData()
    {
        return $this->cache->remember(
            self::IDENTIFIER . '-pool-data',
            [self::class, 'updateData'],
            self::EXPIRATION * MINUTE_IN_SECONDS
        );
    }


    public static function updateData()
    {
        $application = Application::getInstance();

        if (! $application->isReady()) {
            return new WP_Error(self::IDENTIFIER, __('Application not ready', 'cardanopress-ispo'));
        }

        $poolData = [];

        foreach ($application->option('delegation_pool_id') as $queryNetwork => $poolId) {
            if (! Blockfrost::isReady($queryNetwork)) {
                continue;
            }

            $blockfrost = new Blockfrost($queryNetwork);
            $information = $blockfrost->getPoolInfo($poolId);
            $metaData = $blockfrost->getPoolDetails($poolId);
            $poolData[$queryNetwork] = array_merge($information, $metaData);
        }

        if (empty($poolData)) {
            return new WP_Error(self::IDENTIFIER, __('Blockfrost not ready', 'cardanopress-ispo'));
        }

        return $poolData;
    }
}
