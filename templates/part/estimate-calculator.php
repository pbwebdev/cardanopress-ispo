<?php

/**
 * The template for displaying the estimate reward calculator.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/part/estimate-calculator.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

?>

<div class="mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <label for="delegate-field" class="form-label mb-0">Delegated</label>
        </div>

        <div class="col-auto">
            <input
                x-model="delegate"
                class="form-control fw-bold"
                type="number"
                step="1"
                x-bind:min="minimum"
                x-bind:max="maximum"
            >
        </div>
    </div>

    <input
        x-model="delegate"
        id="delegate-field"
        class="form-range"
        type="range"
        step="1"
        x-bind:min="minimum"
        x-bind:max="maximum"
    >

    <div class="row align-items-center justify-content-between">
        <div class="col-auto"><span x-text="minimum"></span> ADA</div>
        <div class="col-auto"><span x-text="maximum"></span> ADA</div>
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
        x-bind:min="control"
        x-bind:max="limit"
    >

    <div class="row justify-content-between">
        <div class="col-auto">Epoch <span x-text="commence"></span></div>
        <div class="col-auto">Epoch <span x-text="conclude"></span></div>
    </div>
</div>
