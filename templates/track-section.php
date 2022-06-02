<?php

/**
 * The template for displaying the track section.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/delegate-section.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

$rewards = cpISPO()->userProfile()->getCalculatedRewards();

?>

<h2>Track</h2>

<div class="py-3 h-100 flex flex-col justify-content-between">
    <div class="">
        <div class="input-group">
            <input x-model="address" type="text" class="form-control" placeholder="Stake Address">
            <button class="btn btn-outline-secondary" @click="handleTracking()" x-bind:disabled="!address">Track</button>
        </div>

        <template x-if="trackedReward">
            <div class="mt-3">
                <h3>Rewards</h3>

                <input x-bind:value="trackedReward" type="text" class="form-control" readonly disabled>
            </div>
        </template>
    </div>

    <template x-if="isConnected">
        <div class="mt-3">
            <h3>Connected Wallet Rewards</h3>

            <input value="<?php echo number_format($rewards, 6); ?>" type="text" class="form-control" readonly disabled>
        </div>
    </template>
</div>

