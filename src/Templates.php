<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\ISPO;

use CardanoPress\Foundation\AbstractTemplates;
use CardanoPress\Traits\HasPageTemplates;

class Templates extends AbstractTemplates
{
    use HasPageTemplates;

    public const OVERRIDES_PREFIX = 'cardanopress/ispo/';

    protected function initialize(): void
    {
        $this->setPageTitlePrefix('CP - ISPO:');
        $this->searchPageTemplates($this->path . 'page/*.php', self::OVERRIDES_PREFIX);
    }

    public function setupHooks(): void
    {
        parent::setupHooks();

        add_filter('theme_page_templates', [$this, 'mergePageTemplates']);
    }
}
