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

<main class="container">
    <div
        x-data="cardanoPressISPO"
        class="py-5"
        data-ration="<?php echo $ration; ?>"
        data-minimum="<?php echo $minAda; ?>"
        data-maximum="<?php echo $maxAda; ?>"
        data-duration="<?php echo $duration; ?>"
    >
        <div class="row">
            <div class="col col-sm-6">
                <?php cpISPO()->template('estimate-section'); ?>
            </div>

            <div class="col col-sm-6 flex flex-col">
                <?php cpISPO()->template('track-section'); ?>
            </div>
        </div>

        <hr>

        <div class="py-3">
            <?php cpISPO()->template('delegate-section'); ?>
        </div>
    </div>
</main>

<?php

get_footer();
