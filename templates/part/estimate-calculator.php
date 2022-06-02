<?php

/**
 * The template for displaying the estimate reward calculator.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/part/estimate-calculator.php.
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

<div class="mb-3">
    <label for="delegate-field" class="form-label">Delegated</label>
    <span x-text="delegate" class="form-label ms-2 fw-bold"></span>

    <input
        x-model="delegate"
        id="delegate-field"
        class="form-range"
        type="range"
        step="1"
        min="<?php echo $minAda; ?>"
        max="<?php echo $maxAda; ?>"
    >

    <div class="row justify-content-between">
        <div class="col-auto"><?php echo $minAda; ?> ADA</div>
        <div class="col-auto"><?php echo $maxAda; ?> ADA</div>
    </div>
</div>

<div class="mb-3">
    <label for="epochs-field" class="form-label">Number of Epochs</label>
    <span x-text="epochs" class="form-label ms-2 fw-bold"></span>

    <input
        x-model="epochs"
        id="epochs-field"
        class="form-range"
        type="range"
        step="1"
        min="1"
        max="<?php echo $duration; ?>"
    >

    <div class='row justify-content-between'>
        <div class='col-auto'>1</div>
        <div class="col-auto"><?php echo $duration; ?></div>
    </div>
</div>
