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
        $poolIds = $this->application->option('delegation_pool_id');

        if ('' !== $poolIds['mainnet']) {
            return;
        }

        $message = sprintf(
            '<strong>%1$s</strong> requires a delegation pool ID. %2$s',
            $this->application->getData('Name'),
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
    }
}
