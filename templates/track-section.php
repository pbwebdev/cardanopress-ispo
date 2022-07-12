<?php

/**
 * The template for displaying the track section.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/track-section.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

?>

<div class="col col-md-10 mx-auto">
    <div class="card m-3 p-3">
        <div class="row align-items-center">
            <div class="col-8">
                <template x-if="!isConnected">
                    <p class="mb-0">Connect your wallet to check your reward balance.</p>
                </template>

                <template x-if="isConnected">
                    <p class="mb-0">Connected wallet reward balance.</p>
                </template>
            </div>

            <div class="col">
                <?php cpISPO()->template('part/wallet-rewards'); ?>
            </div>
        </div>
    </div>

    <div class="card m-3 p-3">
        <div class="row align-items-center">
            <div class="col-8">
                <p>Enter your wallet stake address (BECH32 format).</p>

                <?php cpISPO()->template('part/track-process'); ?>
            </div>

            <div class="col">
                <?php cpISPO()->template('part/track-result'); ?>
            </div>
        </div>
    </div>
</div>

