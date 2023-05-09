<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\ISPO;

use CardanoPress\Helpers\NumberHelper;
use PBWebDev\CardanoPress\Blockfrost;

class Manager
{
    public static function calculateReward(float $ration, int $lovelace, float $multiplier): float
    {
        return $ration / 100 * NumberHelper::lovelaceToAda($lovelace) * $multiplier;
    }

    public static function getRewards(string $stakeAddress, string $queryNetwork): float
    {
        $blockfrost = new Blockfrost($queryNetwork);
        $application = Application::getInstance();
        $poolIds = $application->option('delegation_pool_id');
        $ration = $application->option('rewards_ration');
        $multiplier = $application->option('rewards_multiplier');
        $commence = $application->option('rewards_commence');
        $conclude = $application->option('rewards_conclude');
        $customRewards = apply_filters(
            'cp-ispo-force_wallet_rewards',
            null,
            $stakeAddress,
            compact('ration', 'multiplier')
        );

        if (null !== $customRewards) {
            return $customRewards;
        }

        $rewards = 0;
        $page = 1;

        do {
            $response = $blockfrost->getAccountHistory($stakeAddress, $page);

            foreach ($response as $history) {
                if ($history['pool_id'] !== $poolIds[$queryNetwork]) {
                    continue;
                }

                if ($history['active_epoch'] >= $commence && $history['active_epoch'] <= $conclude) {
                    do_action('cp-ispo-qualified_epoch_for_rewards', $history['active_epoch'], $stakeAddress);

                    $lovelace = $history['amount'];
                    $rewards += apply_filters(
                        'cp-ispo-epoch_calculated_reward',
                        self::calculateReward($ration, $lovelace, $multiplier),
                        $history['active_epoch'],
                        compact('ration', 'lovelace', 'multiplier', 'rewards'),
                    );
                }
            }

            $page++;
        } while (100 === count($response));

        if (0 === $rewards) {
            return apply_filters('cp-ispo-total_accumulated_rewards', $rewards, compact('ration', 'multiplier'));
        }

        $latest = $blockfrost->getEpochsLatest();

        if (empty($latest) || $latest['epoch'] > $conclude) {
            return apply_filters('cp-ispo-total_accumulated_rewards', $rewards, compact('ration', 'multiplier'));
        }

        $response = $blockfrost->getAccountDetails($stakeAddress);

        if (! empty($response) && $response['active'] && $response['pool_id'] === $poolIds[$queryNetwork]) {
            do_action('cp-ispo-qualified_epoch_for_rewards', $latest['epoch'], $stakeAddress);

            $lovelace = $response['controlled_amount'];
            $rewards += apply_filters(
                'cp-ispo-epoch_calculated_reward',
                self::calculateReward($ration, $lovelace, $multiplier),
                $latest['epoch'],
                compact('ration', 'lovelace', 'multiplier', 'rewards'),
            );
        }

        return apply_filters('cp-ispo-total_accumulated_rewards', $rewards, compact('ration', 'multiplier'));
    }
}
