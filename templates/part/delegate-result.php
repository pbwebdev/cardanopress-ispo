<?php

/**
 * The template for displaying the delegate result section.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/part/delegate-result.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

?>

<template x-if="isConnected && transactionHash">
    <div class="input-group">
        <input x-bind:value="transactionHash" type="text" class="form-control" readonly disabled>
        <button class="btn btn-outline-secondary" x-on:click.prevent="clipboardValue(transactionHash)">Copy</button>
    </div>
</template>
