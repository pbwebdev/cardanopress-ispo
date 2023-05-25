<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\ISPO;

use CardanoPress\Helpers\NumberHelper;
use CardanoPress\ISPO\Dependencies\League\Csv\Writer;
use Exception;
use Psr\Log\LoggerInterface;
use CardanoPress\Interfaces\HookInterface;
use CardanoPress\Traits\Loggable;
use PBWebDev\CardanoPress\Blockfrost;

class Exporter implements HookInterface
{
    use Loggable;

    public const ACTION = 'cp-ispo_exporter_action';

    public function __construct(LoggerInterface $logger)
    {
        $this->setLogger($logger);
    }

    public function setupHooks(): void
    {
        add_action('wp_ajax_' . self::ACTION, [$this, 'doAction']);
    }

    public static function actionButton(string $network): string
    {
        return sprintf(
            '<a href="%1$s" class="button" target="_blank">%2$s</a>',
            add_query_arg(
                [
                    'action' => self::ACTION,
                    'nonce' => wp_create_nonce(self::ACTION),
                    'network' => $network,
                ],
                admin_url('admin-ajax.php')
            ),
            __('Start Process', 'cardanopress-ispo')
        );
    }

    public function doAction(): void
    {
        if (! wp_verify_nonce($_REQUEST['nonce'] ?? '', self::ACTION)) {
            $this->dieHandler('forbidden');
        }

        $network = sanitize_key($_GET['network'] ?? '');

        if (empty($network)) {
            $this->dieHandler('unknown');
        }

        $pools = Application::getInstance()->option('delegation_pool_id');
        $pool_id = $pools[$network] ?? '';

        if (empty($pool_id)) {
            wp_die(__('Unknown pool to process', 'cardanopress-ispo'));
        }

        try {
            $csv = Writer::createFromStream(tmpfile());

            $this->log(__METHOD__ . ' ' . $pool_id);
            $csv->insertOne(['stake_address', 'lovelace_amount', 'calculated_reward']);
            $csv->insertAll($this->getData($network, $pool_id));
            $csv->output(date('Y-m-d-H-i') . '-data.csv');
        } catch (Exception $exception) {
            $this->log($exception->getMessage());
        }

        $this->dieHandler('success');
    }

    protected function dieHandler(string $type)
    {
        $types = [
            'success' => [
                'message' => '',
                'code' => 200,
            ],
            'unknown' => [
                'message' => __('Unknown network to process', 'cardanopress-ispo'),
                'code' => 400,
            ],
            'forbidden' => [
                'message' => __('Invalid nonce value provided', 'cardanopress-ispo'),
                'code' => 403,
            ],
        ];

        wp_die($types[$type]['message'], __('Export Data', 'cardanopress-ispo'), $types[$type]['code']);
    }

    protected function getData(string $network, string $pool_id): array
    {
        $application = Application::getInstance();
        $ration = $application->option('rewards_ration');
        $multiplier = $application->option('rewards_multiplier');
        $blockfrost = new Blockfrost($network);
        $delegations = [];
        $page = 1;

        do {
            $response = $this->requestData($blockfrost, $pool_id, $page);

            $this->log(__METHOD__ . ' count=' . count($response));

            foreach ($response as $delegation) {
                $stake_address = $delegation['address'];
                $lovelace_amount = $delegation['live_stake'];
                $calculated_reward = $ration / 100 * NumberHelper::lovelaceToAda($lovelace_amount) * $multiplier;
                $delegations[] = compact('stake_address', 'lovelace_amount', 'calculated_reward');
            }

            $page++;
        } while (100 === count($response));

        return $delegations;
    }

    protected function requestData(Blockfrost $blockfrost, string $pool_id, int $page): array
    {
        $this->log(__METHOD__ . ' page #' . $page);

        $response = $blockfrost->request('pools/' . $pool_id . '/delegators', compact('page'));

        return 200 === $response['status_code'] ? $response['data'] : [];
    }
}
