<?php

/**
 * Page template for displaying ISPO workflow.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/page/Dashboard.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

use CardanoPress\Helpers\NumberHelper;
use PBWebDev\CardanoPress\ISPO\Actions;

$ration = cpISPO()->option('rewards_ration');
$minAda = cpISPO()->option('rewards_minimum');
$maxAda = cpISPO()->option('rewards_maximum');
$commence = cpISPO()->option('rewards_commence');
$conclude = cpISPO()->option('rewards_conclude');
$tokens = cpISPO()->option('allocated_tokens');
$pool = cpISPO()->delegationPool();
$network = cpISPO()->userProfile()->connectedNetwork();
$link = Actions::getCardanoscanLink($network, 'pool/');

cardanoPress()->compatibleHeader();

?>

<main>
    <div
        x-data="cardanoPressISPO"
        data-ration="<?php echo esc_attr($ration); ?>"
        data-minimum="<?php echo esc_attr($minAda); ?>"
        data-maximum="<?php echo esc_attr($maxAda); ?>"
        data-commence="<?php echo esc_attr($commence); ?>"
        data-conclude="<?php echo esc_attr($conclude); ?>"
    >
        <section id="banner">
            <div class="container-xxl py-5">
                <div class="col col-md-8 mx-auto text-center">
                    <h1><?php echo esc_html($pool['name']); ?> Initial Stake Pool Offering (ISPO)</h1>
                    <p>We are currently running our ISPO to distribute the <span class="fw-bold"><?php echo esc_html($pool['ticker']); ?></span> tokens to the delegates of the project.</p>
                    <p>Delegate your Cardano wallet to earn your rewards.</p>
                    <p>Starts: <?php echo Actions::toUTC($commence); ?> UTC | Ends: <?php echo Actions::toUTC($conclude); ?> UTC</p>
                    <?php if ($tokens) : ?>
                        <p>Allocated Tokens: <?php echo number_format($tokens); ?></p>
                    <?php endif; ?>
                    <a class="btn btn-primary" href="#calculator">Calculate Rewards</a>
                    <a class="btn btn-secondary" href="#pool-delegate">Delegate</a>
                </div>
            </div>
        </section>

        <section id="calculator" class="bg-light">
            <div class="container py-5">
                <div class="col col-md-8 mx-auto text-center">
                    <h2>Rewards Calculator Estimator</h2>
                    <p>Check your potential rewards for delegating your ADA to our stake pool over a period of time.</p>
                    <p>See how the extended loyalty bonuses can affect your final rewards.</p>
                </div>

                <?php cpISPO()->template('estimate-section'); ?>
            </div>
        </section>

        <div id="check-rewards">
            <div class="container py-5">
                <div class="col col-md-8 mx-auto text-center">
                    <h2>Check Your Rewards</h2>
                    <p>Check your potential rewards for delegating your ADA to our stake pool over a period of time. See how the extended loyalty bonuses can affect your final rewards.</p>
                </div>

                <?php cpISPO()->template('track-section'); ?>
            </div>
        </div>

        <div id="pool-delegate" class="bg-light">
            <div class="container py-5">
                <div class="col col-md-8 mx-auto text-center">
                    <h2>Pool Stats & Delegate Your ADA</h2>
                    <p>Check the pool and ensure that you're delegating to the correct and official one.</p>
                    <p>Search for the pool ticker <span class="fw-bold"><?php echo esc_html($pool['ticker']); ?></span>, or search using the Pool ID. Ensure you are delegating to the correct stake pool.</p>
                    <p>Pool ID: <span class="fw-bold"><?php echo esc_html($pool['pool_id']); ?></span></p>
                    <span class="m-1">
                        <a href="#" x-on:click.prevent="clipboardValue('<?php echo esc_js($pool['pool_id']); ?>')" title="Copy to clipboard">Copy</a>
                    </span>
                    <span class="m-1">
                        <a href="<?php echo esc_url($link . $pool['hex']); ?>" target="_blank" title="View on Cardanoscan">View</a>
                    </span>
                </div>

                <div class="row m-3">
                    <div class="mb-4 col">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="fs-4 mb-0">
                                        <?php echo esc_html(NumberHelper::shortRounded(NumberHelper::lovelaceToAda($pool['active_stake']))); ?>
                                    </p>
                                    <p class="text-sm text-muted mb-0">Active Stake</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 col">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="fs-4 mb-0"><?php echo esc_html(NumberHelper::shortRounded($pool['live_delegators'])); ?></p>
                                    <p class="text-sm text-muted mb-0">Delegates</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 col">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="fs-4 mb-0"><?php echo esc_html(NumberHelper::shortRounded($pool['blocks_minted'])); ?></p>
                                    <p class="text-sm text-muted mb-0">Blocks Minted</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php cpISPO()->template('delegate-section'); ?>
            </div>
        </div>
    </div>
</main>

<?php

cardanoPress()->compatibleFooter();
