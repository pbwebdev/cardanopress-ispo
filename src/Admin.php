<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\ISPO;

use CardanoPress\Dependencies\ThemePlate\Meta\PostMeta;
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
            $this->poolSettings();
            $this->dashboardSettings();
        });
    }

    protected function generalFields(): array
    {
        return [
            'allocated_tokens' => [
                'title' => __('Allocated Tokens', 'cardanopress-ispo'),
                'type' => 'number',
                'default' => '',
            ],
        ];
    }

    protected function delegationFields(): array
    {
        return [
            'pool_id' => [
                'title' => __('Bech32 ID', 'cardanopress-ispo'),
                'type' => 'text',
            ],
        ];
    }

    protected function rewardsFields(): array
    {
        return [
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
        ];
    }

    public function poolSettings(): void
    {
        $fields = $this->delegationFields() + [
            'network' => [
                'type' => 'hidden',
                'default'  => 'mainnet',
            ],
        ] + $this->generalFields() + $this->rewardsFields();

        $this->optionFields(__('Pool Settings', 'cardanopress-ispo'), [
            'data_prefix' => '',
            'fields' => [
                'settings' => [
                    'type' => 'group',
                    'style' => 'ispo-pool-settings',
                    'repeatable' => true,
                    'fields' => $fields,
                    'default' => array_map(function ($field) {
                        return $field['default'] ?? '';
                    }, $fields),
                ],
            ],
        ]);
    }

    public function dashboardSettings(): void
    {
        $postMeta = new PostMeta(__('ISPO Settings', 'cardanopress-ispo'), [
            'data_prefix' => self::OPTION_KEY . '_',
            'show_on' => [
                'key' => 'template',
                'value' => 'Dashboard.php',
            ]
        ]);

        $postMeta->fields([
            'pool_id' => [
                'title' => __('Showcase Pool', 'cardanopress-ispo'),
                'type' => 'select',
                'options' => array_combine(Manager::getPoolIDs(), Manager::getPoolIDs()),
            ],
        ])->location('page')->create();

        $this->storeConfig($postMeta->get_config());
    }
}
