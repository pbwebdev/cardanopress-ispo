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

<h2>Estimate</h2>

<div class="py-3">
    <?php cpISPO()->template('part/estimate-calculator', compact('ration', 'minAda', 'maxAda', 'duration')); ?>

    <?php cpISPO()->template('part/estimate-result'); ?>
</div>
