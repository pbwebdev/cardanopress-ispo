<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\ISPO;

use CardanoPress\Foundation\AbstractAdmin;

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
                'menu_title' => 'ISPO Settings',
            ]);
        });

        add_action('init', function () {
            $this->generalSettings();
            $this->delegationSettings();
            $this->rewardsSettings();
        });
    }

    public function generalSettings(): void
    {
        $this->optionFields(__('General Settings', 'cardanopress-ispo'), [
            'data_prefix' => '',
            'fields' => [
                'allocated_tokens' => [
                    'title' => __('Allocated Tokens', 'cardanopress-ispo'),
                    'type' => 'number',
                    'default' => '',
                ],
            ],
        ]);
    }

    public function delegationSettings(): void
    {
        $this->optionFields(__('Delegation Settings', 'cardanopress-ispo'), [
            'data_prefix' => 'delegation_',
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
                            'required' => true,
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
        $this->optionFields(__('Rewards Settings', 'cardanopress-ispo'), [
            'data_prefix' => 'rewards_',
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
                'multiplier' => [
                    'title' => __('Loyalty multiplier', 'cardanopress-ispo'),
                    'description' => __('Every qualifying epoch', 'cardanopress-ispo'),
                    'type' => 'number',
                    'default' => 1,
                    'options' => [
                        'min' => 1,
                        'step' => 0.001,
                    ],
                ],
            ],
        ]);
    }
}
