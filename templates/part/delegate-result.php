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

<h3>Delegation Result</h3>

<template x-if="isConnected && transactionHash">
    <div class="input-group">
        <p><a href='https://cardanoscan.io/transaction/x-bind:value="transactionHash"' target="_blank">View transaction</a> on CardanoScan.</p>
        <input x-bind:value="transactionHash" type="text" class="form-control" readonly disabled>
        <button class="btn btn-outline-secondary" @click.prevent="clipboardValue(transactionHash)">Copy</button>
    </div>
</template>
