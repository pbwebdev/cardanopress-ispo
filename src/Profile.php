<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\ISPO;

use CardanoPress\Foundation\AbstractProfile;

class Profile extends AbstractProfile
{
    private string $prefix = 'cp_ispo_';

    protected function initialize(): void
    {
    }

    public function saveCalculatedRewards(float $rewards): bool
    {
        return $this->updateMeta($this->prefix . 'calculated_rewards', $rewards);
    }

    public function getCalculatedRewards(): float
    {
        $saved = $this->getMeta($this->prefix . 'calculated_rewards', true);

        return $saved ?: 0.0;
    }

    public function saveExtraRewards(array $rewards): bool
    {
        return $this->updateMeta($this->prefix . 'extra_rewards', $rewards);
    }

    public function getExtraRewards(): array
    {
        $saved = $this->getMeta($this->prefix . 'extra_rewards', true);

        return $saved ?: [];
    }
}
