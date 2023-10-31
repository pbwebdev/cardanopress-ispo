<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\ISPO;

use CardanoPress\Foundation\AbstractInstaller;
use CardanoPress\Traits\HasSettingsLink;

class Installer extends AbstractInstaller
{
    use HasSettingsLink;

    public const DATA_PREFIX = 'cp_ispo_';

    protected function initialize(): void
    {
        $this->setSettingsLinkUrl(admin_url('admin.php?page=' . Admin::OPTION_KEY));
    }

    public function setupHooks(): void
    {
        parent::setupHooks();

        add_action('admin_notices', [$this, 'noticeNeedingCorePlugin']);
        add_action('admin_notices', [$this, 'noticeApplicationNotReady']);
        add_action(self::DATA_PREFIX . 'upgrading', [$this, 'doUpgrade'], 10, 2);
        add_filter('plugin_action_links_' . $this->pluginBaseName, [$this, 'mergeSettingsLink']);
    }

    public function noticeApplicationNotReady(): void
    {
        if (! empty(Manager::getPoolIDs())) {
            return;
        }

        $message = sprintf(
            /* translators: 1: plugin name 2: settings link */
            __('%1$s requires a delegation pool ID. %2$s', 'cardanopress-ispo'),
            '<strong>' . $this->application->getData('Name') . '</strong>',
            $this->getSettingsLink(__('Please set here', 'cardanopress-ispo'), '_blank')
        );

        ?>
        <div class="notice notice-info">
            <p><?php echo wp_kses($message, [
                'a' => [
                    'href' => [],
                    'target' => [],
                ],
                'strong' => [],
            ]); ?></p>
        </div>
        <?php
    }

    public function doUpgrade(string $currentVersion, string $appVersion): void
    {
        if (version_compare($currentVersion, '1.3.0', '<')) {
            $this->migrateSettings();
        }
    }

    public function migrateSettings(): void
    {
        $this->log(__('Migrating settings', 'cardanopress'));

        $optionsValue = get_option(Admin::OPTION_KEY, []);

        if (empty($optionsValue)) {
            return;
        }

        $optionsValue['settings'] = [];

        foreach ($optionsValue['delegation_pool_id'] as $poolId) {
            $optionsValue['settings'][] = [
                'pool_id' => $poolId,
                'allocated_tokens' => $optionsValue['allocated_tokens'],
                'ration' => $optionsValue['rewards_ration'],
                'minimum' => $optionsValue['rewards_minimum'],
                'maximum' => $optionsValue['rewards_maximum'],
                'commence' => $optionsValue['rewards_commence'],
                'conclude' => $optionsValue['rewards_conclude'],
                'multiplier' => $optionsValue['rewards_multiplier'],
            ];
        }

        update_option(Admin::OPTION_KEY, $optionsValue);
    }
}
