<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\ISPO;

use CardanoPress\Interfaces\HookInterface;
use PBWebDev\CardanoPress\Blockfrost;
use WP_User;

class Actions implements HookInterface
{
    public function setupHooks(): void
    {
        add_action('cardanopress_wallet_status_checks', [$this, 'getAccountDetails'], 10, 2);
        add_action('wp_ajax_nopriv_cp-ispo_track_rewards', [$this, 'getStakeRewards']);
        add_action('wp_ajax_cp-ispo_track_rewards', [$this, 'getStakeRewards']);
        add_action('wp_ajax_cp-ispo_delegation_data', [$this, 'getDelegationData']);
    }

    public function getAccountDetails(WP_User $user, Blockfrost $blockfrost)
    {
        $userProfile = new Profile($user);
        $queryNetwork = $userProfile->connectedNetwork();
        $response = $blockfrost->getAccountDetails($queryNetwork);

        if (empty($response)) {
            return;
        }

        $rewards = $this->calculateRewards($response, $queryNetwork);

        $userProfile->saveCalculatedRewards($rewards);
        $userProfile->saveAccountDetails($response);
    }

    protected function getNetworkFromStake(string $address): string
    {
        return 0 === strpos($address, 'stake1') ? 'mainnet' : 'testnet';
    }

    protected function calculateRewards(array $details, string $queryNetwork): float
    {
        if (! $details['active'] ?? '') {
            return 0;
        }

        $application = Application::getInstance();
        $poolIds = $application->option('delegation_pool_id');

        if ($poolIds[$queryNetwork] !== $details['pool_id']) {
            return 0;
        }

        // TODO correct calculation
        $ration = $application->option('rewards_ration');
        $adaValue = $details['controlled_amount'];

        return $ration / 100 * $adaValue;
    }

    public function getStakeRewards(): void
    {
        check_ajax_referer('cardanopress-actions');

        if (empty($_POST['stakeAddress']) || ! Application::getInstance()->isReady()) {
            wp_send_json_error(__('Something is wrong. Please try again', 'cardanopress-ispo'));
        }

        $queryNetwork = $this->getNetworkFromStake($_POST['stakeAddress']);
        $blockfrost = new Blockfrost($queryNetwork);
        $response = $blockfrost->getAccountDetails($_POST['stakeAddress']);

        if (empty($response)) {
            wp_send_json_error(__('Something is wrong. Please try again', 'cardanopress-ispo'));
        }

        wp_send_json_success($this->calculateRewards($response, $queryNetwork));
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
