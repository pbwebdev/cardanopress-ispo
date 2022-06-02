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

    public function saveAccountDetails(array $data): bool
    {
        return $this->updateMeta($this->prefix . 'account_details', $data);
    }

    public function getAccountDetails(): array
    {
        $saved = $this->getMeta($this->prefix . 'account_details', true);

        return $saved ?: [];
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
}
