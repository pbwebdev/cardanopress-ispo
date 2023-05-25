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
                    'network' => $network,
                ],
                admin_url('admin-ajax.php')
            ),
            __('Start Process', 'cardanopress-ispo')
        );
    }

    public function doAction(): void
    {

        try {
            $csv = Writer::createFromStream(tmpfile());

            $csv->insertOne(['stake_address', 'lovelace_amount', 'calculated_reward']);
            $csv->insertAll($this->getData($_GET['network']));
            $csv->output(date('Y-m-d-H-i') . '-data.csv');
        } catch (Exception $exception) {
            $this->log($exception->getMessage());
        }
    }

    protected function getData(string $network): array
    {
        $application = Application::getInstance();
        $pools = $application->option('delegation_pool_id');
        $ration = $application->option('rewards_ration');
        $multiplier = $application->option('rewards_multiplier');
        $blockfrost = new Blockfrost($network);
        $delegations = [];
        $page = 1;

        do {
            $response = $this->requestData($blockfrost, $pools[$network], $page);

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
        $response = $blockfrost->request('pools/' . $pool_id . '/delegators', compact('page'));

        return 200 === $response['status_code'] ? $response['data'] : [];
    }
}
