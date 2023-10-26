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
use CardanoPress\Dependencies\ThemePlate\Cache\CacheManager;
use CardanoPress\Dependencies\ThemePlate\Process\Tasks;
use WP_Error;

class PoolManager
{
    use Instantiable;
    use Loggable;

    protected Tasks $tasks;
    protected CacheManager $cache;

    public const IDENTIFIER = 'cp-ispo';
    public const EXPIRATION = 15; // in minutes

    public const DATA_STRUCTURE = [
        'pool_id' => '',
        'hex' => '',
        'vrf_key' => '',
        'blocks_minted' => 0,
        'blocks_epoch' => 0,
        'live_stake' => 0,
        'live_size' => 0.0,
        'live_saturation' => 0.0,
        'live_delegators' => 0,
        'active_stake' => 0,
        'active_size' => 0.0,
        'declared_pledge' => 0,
        'live_pledge' => 0,
        'margin_cost' => 0.0,
        'fixed_cost' => 0,
        'reward_account' => '',
        'owners' => [''],
        'registration' => [''],
        'retirement' => [''],
        'url' => '',
        'hash' => '',
        'ticker' => '',
        'name' => '',
        'description' => '',
        'homepage' => '',
    ];

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
        $keys = cardanoPress()->option('blockfrost_project_id');

        Blockfrost::useProjectIds($keys['mainnet'] ?? '', $keys['testnet'] ?? '');

        foreach ($application->option('settings') as $setting) {
            $poolId = $setting['pool_id'];
            $queryNetwork = $setting['network'] ?? 'mainnet';

            if (empty($poolId)) {
                continue;
            }

            if (!Blockfrost::isReady($queryNetwork)) {
                $poolData[$poolId] = [];

                continue;
            }

            $blockfrost = new Blockfrost($queryNetwork);
            $information = $blockfrost->getPoolInfo($poolId);
            $metaData = $blockfrost->getPoolDetails($poolId);
            $poolData[$poolId] = array_merge($information, $metaData);
        }

        if (empty($poolData)) {
            return new WP_Error(self::IDENTIFIER, __('Blockfrost not ready', 'cardanopress-ispo'));
        }

        return $poolData;
    }
}
