<?php

/**
 * Page template for displaying ISPO workflow.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/page/Dashboard.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

$ration = cpISPO()->option('rewards_ration');
$minAda = cpISPO()->option('rewards_minimum');
$maxAda = cpISPO()->option('rewards_maximum');
$duration = cpISPO()->option('rewards_duration');

get_header();

?>

<main class="">
    <div
        x-data="cardanoPressISPO"
        class="py-5"
        data-ration="<?php echo $ration; ?>"
        data-minimum="<?php echo $minAda; ?>"
        data-maximum="<?php echo $maxAda; ?>"
        data-duration="<?php echo $duration; ?>"
    >

        <section id="banner" class="bd-masthead py-5">
            <div class="container-xxl">
                <div class="col-md-8 mx-auto text-center">
                    <div class="banner">
                        <h1>GoKey Initial Stake Pool Offering (ISPO)</h1>
                        <p>We are currently running our ISPO to distribute the GoKey tokens to the delegates of the project. Delegate your Cardano wallet to the pool ticker: GOKEY to earn your rewards.</p>
                        <a class="btn btn-primary" href="#calculator">Calculate Rewards</a>
                        <a class="btn btn-secondary" href="#pool-delegate">Delegate</a>
                    </div><!-- .banner -->
                </div>
            </div>
        </section>

        <section id="calculator" class="bg-light">
            <div class="container py-5">
                <?php cpISPO()->template('estimate-section'); ?>
            </div>
        </section>

        <div id="check-rewards">
            <div class="container py-5">
                <div class=" col-md-8 mx-auto">
                    <?php cpISPO()->template('track-section'); ?>
                </div>
            </div>
        </div>


        <div id="pool-delegate" class="bg-light">
            <div class="container col-md-8 mx-auto py-5 ">
                <?php cpISPO()->template('delegate-section'); ?>
            </div>
        </div>

    </div>
</main>

<?php

get_footer();
