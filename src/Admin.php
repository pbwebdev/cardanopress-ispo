<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\ISPO;

use CardanoPress\Foundation\AbstractAdmin;
use PBWebDev\CardanoPress\Blockfrost;

class Admin extends AbstractAdmin
{
    public const OPTION_KEY = 'cp-ispo';

    protected function initialize(): void
    {
    }

    public function setupHooks(): void
    {
        add_action('plugins_loaded', function () {
            $this->settingsPage('CardanoPress - ISPO', [
                'parent' => Application::getInstance()->isReady() ? 'cardanopress' : '',
                'menu' => 'ISPO Settings',
            ]);
        });

        add_action('init', function () {
            $this->delegationSettings();
            $this->rewardsSettings();
        });
        add_filter('pre_update_option_' . self::OPTION_KEY, [$this, 'getPoolDetails'], 10, 2);
    }

    public function delegationSettings(): void
    {
        $this->optionFields([
            'id' => 'delegation',
            'title' => __('Delegation Settings', 'cardanopress-ispo'),
            'fields' => [
                'pool_id' => [
                    'type' => 'group',
                    'default' => [
                        'mainnet' => '',
                        'testnet' => '',
                    ],
                    'fields' => [
                        'mainnet' => [
                            'title' => __('Mainnet', 'cardanopress-ispo'),
                            'type' => 'text',
                        ],
                        'testnet' => [
                            'title' => __('Testnet', 'cardanopress-ispo'),
                            'type' => 'text',
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function rewardsSettings(): void
    {
        $this->optionFields([
            'id' => 'rewards',
            'title' => __('Rewards Settings', 'cardanopress-ispo'),
            'fields' => [
                'ration' => [
                    'title' => __('Ratio Per ADA', 'cardanopress-ispo'),
                    'description' => __('In percentage', 'cardanopress-ispo'),
                    'type' => 'number',
                    'default' => 1,
                    'options' => [
                        'min' => 0.001,
                        'max' => 100,
                        'step' => 0.001,
                    ],
                ],
                'minimum' => [
                    'title' => __('Minimum ADA', 'cardanopress-ispo'),
                    'type' => 'number',
                    'default' => 1,
                    'options' => [
                        'min' => 1,
                        'step' => 1,
                    ],
                ],
                'maximum' => [
                    'title' => __('Maximum ADA', 'cardanopress-ispo'),
                    'type' => 'number',
                    'default' => 2,
                    'options' => [
                        'min' => 2,
                        'step' => 1,
                    ],
                ],
                'commence' => [
                    'title' => __('Commence at Epoch', 'cardanopress-ispo'),
                    'type' => 'number',
                    'default' => 1,
                    'options' => [
                        'min' => 1,
                        'step' => 1,
                    ],
                ],
                'conclude' => [
                    'title' => __('Conclude at Epoch', 'cardanopress-ispo'),
                    'type' => 'number',
                    'default' => 2,
                    'options' => [
                        'min' => 2,
                        'step' => 1,
                    ],
                ],
            ],
        ]);
    }

    public function getPoolDetails($newValue, $oldValue)
    {
        if (
            ! empty($oldValue['delegation_pool_data']) &&
            $newValue['delegation_pool_id'] === $oldValue['delegation_pool_id']
        ) {
            return $newValue;
        }

        if (! Application::getInstance()->isReady()) {
            return $newValue;
        }

        $newValue['delegation_pool_data'] = $oldValue['delegation_pool_data'] ?? [];

        foreach ($newValue['delegation_pool_id'] as $network => $poolId) {
            if (! Blockfrost::isReady($network)) {
                continue;
            }

            $blockfrost = new Blockfrost($network);
            $information = $blockfrost->getPoolInfo($poolId);
            $metaData = $blockfrost->getPoolDetails($poolId);
            $newValue['delegation_pool_data'][$network] = array_merge($information, $metaData);
        }

        return $newValue;
    }
}
