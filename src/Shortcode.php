<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\ISPO;

use CardanoPress\Foundation\AbstractShortcode;

class Shortcode extends AbstractShortcode
{
    protected Application $application;

    public function __construct()
    {
        $this->application = Application::getInstance();
    }

    public function setupHooks(): void
    {
        add_shortcode('cp-ispo_component', [$this, 'doComponent']);
        add_shortcode('cp-ispo_template', [$this, 'doTemplate']);
    }

    public function doComponent($attributes, ?string $content = null): string
    {
        $html = sprintf(
            '<div x-data="cardanoPressISPO" data-%s="%s" data-%s="%s" data-%s="%s" data-%s="%s" data-%s="%s">',
            'ration',
            $this->application->option('rewards_ration'),
            'minimum',
            $this->application->option('rewards_minimum'),
            'maximum',
            $this->application->option('rewards_maximum'),
            'commence',
            $this->application->option('rewards_commence'),
            'conclude',
            $this->application->option('rewards_conclude')
        );
        $html .= apply_filters('the_content', $content);
        $html .= '</div>';

        wp_enqueue_script(Manifest::HANDLE_PREFIX . 'script');

        return trim($html);
    }

    public function doTemplate(array $attributes): string
    {
        $args = shortcode_atts([
            'name' => '',
            'variables' => [],
            'if' => '',
        ], $attributes);

        if (empty($args['name'])) {
            return '';
        }

        if (isset($attributes['variables'])) {
            parse_str(str_replace('&amp;', '&', $args['variables']), $args['variables']);
        }

        ob_start();
        $this->application->template($args['name'], $args['variables']);

        $html = ob_get_clean();

        if (empty($args['if'])) {
            return $html;
        }

        return '<template x-if="' . $args['if'] . '">' . $html . '</template>';
    }
}
