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

    public static function actionButton(string $network, string $pool): string
    {
        return sprintf(
            '<a href="%1$s" class="button" target="_blank">%2$s</a>',
            add_query_arg(
                [
                    'action' => self::ACTION,
                    'nonce' => wp_create_nonce(self::ACTION),
                    'network' => $network,
                    'pool' => $pool,
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

        $pool = sanitize_key($_GET['pool'] ?? '');

        if (empty($pool) || ! in_array($pool, Manager::getPoolIDs(), true)) {
            $this->dieHandler('unknown');
        }

        try {
            $csv = Writer::createFromStream(tmpfile());
            $index = array_search($pool, Manager::getPoolIDs(), true);
            $settings = Manager::getSettings($index);

            $this->log(__METHOD__ . ' ' . $pool);
            $csv->insertOne(array_merge(
                ['stake_address', 'total_amount', 'total_reward'],
                array_keys($this->prepareHolder($settings))
            ));
            $csv->insertAll($this->getData($settings));
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
                'message' => __('Unknown pool to process', 'cardanopress-ispo'),
                'code' => 400,
            ],
            'forbidden' => [
                'message' => __('Invalid nonce value provided', 'cardanopress-ispo'),
                'code' => 403,
            ],
        ];

        wp_die($types[$type]['message'], __('Export Data', 'cardanopress-ispo'), $types[$type]['code']);
    }

    protected function prepareHolder(array $settings): array
    {
        $prepared = [];

        for ($i = $settings['commence']; $i <= $settings['conclude']; $i++) {
            $prepared['amount_' . $i] = 0;
            $prepared['reward_' . $i] = 0;
        }

        return $prepared;
    }

    protected function getData(array $settings): array
    {
        $blockfrost = new Blockfrost($settings['network'] ?? 'mainnet');
        $delegations = [];
        $page = 1;

        do {
            $response = $this->requestData($blockfrost, $settings['pool_id'], $page);

            $this->log(__METHOD__ . ' count=' . count($response));

            foreach ($response as $delegation) {
                $this->log(__CLASS__ . '::doScan ' . $delegation['address']);
                $delegations[] = $this->getHistory($blockfrost, $delegation['address'], $settings);
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

    protected function getHistory(Blockfrost $blockfrost, string $stake_address, array $settings): array
    {
        $application = Application::getInstance();
        $ration = $application->option('rewards_ration');
        $multiplier = $application->option('rewards_multiplier');
        $total_amount = 0;
        $total_reward = 0;
        $delegation = array_merge(
            compact('stake_address', 'total_amount', 'total_reward'),
            $this->prepareHolder($settings)
        );
        $page = 1;

        do {
            $response = $this->requestHistory($blockfrost, $stake_address, $page);

            $this->log(__METHOD__ . ' count=' . count($response));

            foreach ($response as $history) {
                if ($history['pool_id'] !== $settings['pool_id']) {
                    continue;
                }

                $active_epoch = $history['active_epoch'];
                $lovelace_amount = $history['amount'];
                $calculated_reward = $ration / 100 * NumberHelper::lovelaceToAda($lovelace_amount) * $multiplier;
                $total_amount += $lovelace_amount;
                $total_reward += $calculated_reward;

                $delegation['amount_' . $active_epoch] = $lovelace_amount;
                $delegation['reward_' . $active_epoch] = $calculated_reward;
            }

            $page++;
        } while (100 === count($response));

        $delegation['total_amount'] = $total_amount;
        $delegation['total_reward'] = $total_reward;

        return $delegation;
    }

    protected function requestHistory(Blockfrost $blockfrost, string $stake_address, int $page): array
    {
        $this->log(__METHOD__ . ' page #' . $page);

        $response = $blockfrost->request('accounts/' . $stake_address . '/history', compact('page'));

        return 200 === $response['status_code'] ? $response['data'] : [];
    }
}
