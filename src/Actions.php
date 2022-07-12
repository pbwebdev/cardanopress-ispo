<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\ISPO;

use CardanoPress\Helpers\NumberHelper;
use CardanoPress\Helpers\WalletHelper;
use CardanoPress\Interfaces\HookInterface;
use PBWebDev\CardanoPress\Blockfrost;
use WP_User;

class Actions implements HookInterface
{
    public function setupHooks(): void
    {
        add_action('cardanopress_wallet_status_checks', [$this, 'getAccountDetails']);
        add_action('wp_ajax_nopriv_cp-ispo_track_rewards', [$this, 'getStakeRewards']);
        add_action('wp_ajax_cp-ispo_track_rewards', [$this, 'getStakeRewards']);
        add_action('wp_ajax_cp-ispo_delegation_data', [$this, 'getDelegationData']);
    }

    public static function getCardanoscanLink(string $network, string $endpoint): string
    {
        $base = [
            'mainnet' => 'https://cardanoscan.io/',
            'testnet' => 'https://testnet.cardanoscan.io/',
        ];

        $network = strtolower($network);

        if (! in_array($network, array_keys($base), true)) {
            $network = 'mainnet';
        }

        return $base[$network] . $endpoint;
    }

    public function getAccountDetails(WP_User $user)
    {
        $userProfile = new Profile($user);
        $queryNetwork = $userProfile->connectedNetwork();

        if (! Blockfrost::isReady($queryNetwork)) {
            return;
        }

        $rewards = $this->calculateRewards($userProfile->connectedStake(), $queryNetwork);

        $userProfile->saveCalculatedRewards($rewards);
    }

    protected function calculateReward(float $ration, int $lovelace, float $multiplier): float
    {
        return $ration / 100 * NumberHelper::lovelaceToAda($lovelace) * $multiplier;
    }

    protected function calculateRewards(string $stakeAddress, string $queryNetwork): float
    {
        $application = Application::getInstance();
        $poolIds = $application->option('delegation_pool_id');
        $blockfrost = new Blockfrost($queryNetwork);
        $ration = $application->option('rewards_ration');
        $commence = $application->option('rewards_commence');
        $conclude = $application->option('rewards_conclude');
        $multiplier = $application->option('rewards_multiplier');
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
                        $this->calculateReward($ration, $lovelace, $multiplier),
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
                $this->calculateReward($ration, $lovelace, $multiplier),
                $latest['epoch'],
                compact('ration', 'lovelace', 'multiplier', 'rewards'),
            );
        }

        return apply_filters('cp-ispo-total_accumulated_rewards', $rewards, compact('ration', 'multiplier'));
    }

    protected function filterStakeAddress(string $inputAddress): string
    {
        if (0 === strpos($inputAddress, 'stake1') || 0 === strpos($inputAddress, 'stake_test1')) {
            return $inputAddress;
        }

        if (0 === strpos($inputAddress, 'addr1') || 0 === strpos($inputAddress, 'addr_test1')) {
            $queryNetwork = WalletHelper::getNetworkFromAddress($inputAddress);

            if (! Blockfrost::isReady($queryNetwork)) {
                wp_send_json_error(sprintf(__('Unsupported network %s', 'cardanopress-ispo'), $queryNetwork));
            }

            $blockfrost = new Blockfrost($queryNetwork);
            $addressDetails = $blockfrost->getAddressDetails($inputAddress);

            return $addressDetails['stake_address'] ?? '';
        }

        return '';
    }

    public function getStakeRewards(): void
    {
        check_ajax_referer('cardanopress-actions');

        if (empty($_POST['stakeAddress']) || ! Application::getInstance()->isReady()) {
            wp_send_json_error(__('Something is wrong. Please try again', 'cardanopress-ispo'));
        }

        $stakeAddress = $this->filterStakeAddress($_POST['stakeAddress']);

        if ('' === $stakeAddress) {
            wp_send_json_error(__('Invalid address format provided.', 'cardanopress-ispo'));
        }

        $queryNetwork = WalletHelper::getNetworkFromStake($stakeAddress);

        if (! Blockfrost::isReady($queryNetwork)) {
            wp_send_json_error(sprintf(__('Unsupported network %s', 'cardanopress-ispo'), $queryNetwork));
        }

        wp_send_json_success($this->calculateRewards($stakeAddress, $queryNetwork));
    }

    public function getDelegationData(): void
    {
        check_ajax_referer('cardanopress-actions');

        $poolData = Application::getInstance()->delegationPool();
        $response = $poolData['hex'] ?? '';

        if (empty($response)) {
            wp_send_json_error(__('Something is wrong. Please try again', 'cardanopress-ispo'));
        }

        wp_send_json_success($response);
    }
}
