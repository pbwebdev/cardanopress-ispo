<?php

/**
 * The template for displaying the track rewards result.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/part/track-result.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

?>

<input x-bind:value="trackedReward" type="text" class="form-control text-center" readonly disabled>

<template x-if="extraRewards">
    <ul class="mt-2">
        <template x-for="reward in extraRewards">
            <li x-text="reward"></li>
        </template>
    </ul>
</template>
