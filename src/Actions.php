<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\ISPO;

use CardanoPress\Helpers\NumberHelper;
use CardanoPress\Helpers\WalletHelper;
use CardanoPress\Interfaces\HookInterface;
use DateTimeZone;
use PBWebDev\CardanoPress\Actions\CoreAction;
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
        add_action('wp_enqueue_scripts', [$this, 'localizeMessages'], 20);
    }

    protected static function customizableMessages(string $type): array
    {
        $data = [
            'script' => [
                'delegating' => __('Processing...', 'cardanopress-ispo'),
                'tracking' => __('Tracking...', 'cardanopress-ispo'),
            ],
            'ajax' => [
                'successfulTrack' => __('Successfully tracked rewards.', 'cardanopress-ispo'),
            ],
            'error' => [
                'invalidAddress' => __('Invalid address format provided.', 'cardanopress-ispo'),
            ],
        ];

        return $data[$type];
    }

    public static function getAjaxMessage(string $type): string
    {
        $messages = apply_filters('cp-ispo-ajax_messages', self::customizableMessages('ajax'));

        return $messages[$type] ?? '';
    }

    public static function getErrorMessage(string $type): string
    {
        $messages = apply_filters('cp-ispo-error_messages', self::customizableMessages('error'));

        return $messages[$type] ?? '';
    }

    public function localizeMessages()
    {
        $messages = apply_filters('cp-ispo-script_messages', $this->customizableMessages('script'));

        wp_localize_script(Manifest::HANDLE_PREFIX . 'script', 'cardanoPressISPOMessages', $messages);
    }

    public static function getCardanoscanLink(string $network, string $endpoint): string
    {
        return WalletHelper::getCardanoscanLink($network, $endpoint);
    }

    public static function toUnixTimestamp(string $epoch): int
    {
        return NumberHelper::toUnixTimestamp((int) $epoch);
    }

    public static function toUTC(string $epoch): string
    {
        $format = get_option('date_format') . ' ' . get_option('time_format');
        $format = apply_filters('cp-ispo-date_format', $format);

        return wp_date($format, self::toUnixTimestamp($epoch), new DateTimeZone('UTC'));
    }

    public function getAccountDetails(WP_User $user)
    {
        $userProfile = new Profile($user);
        $queryNetwork = $userProfile->connectedNetwork();

        if (! Blockfrost::isReady($queryNetwork)) {
            return;
        }

        $rewards = Manager::getRewards($userProfile->connectedStake(), $queryNetwork);

        $userProfile->saveCalculatedRewards($rewards);
    }

    protected function filterStakeAddress(string $inputAddress): string
    {
        $inputAddress = sanitize_text_field($inputAddress);

        if (0 === strpos($inputAddress, 'stake1') || 0 === strpos($inputAddress, 'stake_test1')) {
            return $inputAddress;
        }

        if (0 === strpos($inputAddress, 'addr1') || 0 === strpos($inputAddress, 'addr_test1')) {
            $queryNetwork = WalletHelper::getNetworkFromAddress($inputAddress);

            if (! Blockfrost::isReady($queryNetwork)) {
                wp_send_json_error(sprintf(CoreAction::getAjaxMessage('unsupportedNetwork'), $queryNetwork));
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
            wp_send_json_error(CoreAction::getAjaxMessage('somethingWrong'));
        }

        $stakeAddress = $this->filterStakeAddress($_POST['stakeAddress']);

        if ('' === $stakeAddress) {
            wp_send_json_error(self::getErrorMessage('invalidAddress'));
        }

        $currentUser = wp_get_current_user();

        if (null !== $currentUser) {
            $userProfile = new Profile($currentUser);

            if ($userProfile->connectedStake() === $stakeAddress) {
                wp_send_json_success([
                    'amount' => $userProfile->getCalculatedRewards(),
                    'extra' => apply_filters('cp-ispo-extra_tracked_rewards', null, $stakeAddress),
                    'message' => self::getAjaxMessage('successfulTrack'),
                ]);
            }
        }

        $queryNetwork = WalletHelper::getNetworkFromStake($stakeAddress);

        if (! Blockfrost::isReady($queryNetwork)) {
            wp_send_json_error(sprintf(CoreAction::getAjaxMessage('unsupportedNetwork'), $queryNetwork));
        }

        wp_send_json_success([
            'amount' => Manager::getRewards($stakeAddress, $queryNetwork),
            'extra' => apply_filters('cp-ispo-extra_tracked_rewards', null, $stakeAddress),
            'message' => self::getAjaxMessage('successfulTrack'),
        ]);
    }

    public function getDelegationData(): void
    {
        check_ajax_referer('cardanopress-actions');

        $poolData = Application::getInstance()->delegationPool();
        $response = $poolData['hex'] ?? '';

        if (empty($response)) {
            wp_send_json_error(CoreAction::getAjaxMessage('somethingWrong'));
        }

        wp_send_json_success($response);
    }
}
