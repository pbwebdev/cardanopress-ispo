<?php

/**
 * The template for displaying the estimate section.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/estimate-section.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

if (empty($ration)) {
    $ration = cpISPO()->option('rewards_ration');
}

if (empty($minAda)) {
    $minAda = cpISPO()->option('rewards_minimum');
}

if (empty($maxAda)) {
    $maxAda = cpISPO()->option('rewards_maximum');
}

if (empty($duration)) {
    $duration = cpISPO()->option('rewards_duration');
}

?>


<div class=" col-md-8 mx-auto">
    <div class="row">
        <div class="col text-center col-md-8 mx-auto">
            <h2>Rewards Calculator Estimator</h2>
            <p>Check your potential rewards for delegating your ADA to our stake pool over a period of time. See how the extended loyalty bonuses can affect your final rewards.</p>
        </div>
    </div>

    <div class="row">

        <div class="col-8 p-3">
            <div class="card p-3">
                <p>Use the sliders to change the values to estimate your potential rewards.</p>
                <div class="row">
                    <div class="col-8">
                        <?php cpISPO()->template('part/estimate-calculator', compact('ration', 'minAda', 'maxAda', 'duration')); ?>
<!--                        <label for="customRange1" class="form-label">Amount of ADA</label>-->
<!--                        <input type="range" class="form-range" id="customRange1">-->
                    </div>
                    <div class="col">
<!--                        <span class="h2 primary float-end pt-3">382 ₳</span>-->
                    </div>
                </div>


            </div>
        </div>
        <div class="col p-3 text-center">
            <div class="row">
                <img src="gokey-coin.png" alt="GoKey Coin" style="width: 150px;" class="mx-auto mt-3">
            </div>
            <div class="row rewards-output text-center">
                <?php cpISPO()->template('part/estimate-result'); ?>
<!--                <span class="primary h2">502.23</span> <span>GOKEY</span>-->
            </div>
        </div>
    </div>
</div>

<div class="py-3">

</div>
