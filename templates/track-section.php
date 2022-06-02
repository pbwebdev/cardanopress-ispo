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

<div class="py-3 h-100 flex flex-col justify-content-between">
    <div class="">
        <template x-if="trackedReward">
            <div class="mt-3">
                <h3>Rewards</h3>
                <input x-bind:value="trackedReward" type="text" class="form-control" readonly disabled>
            </div>
        </template>
    </div>
</div>


<div class="row">
    <div class="col text-center col-md-8 mx-auto">
        <h2>Check Your Rewards</h2>
        <p>Check your potential rewards for delegating your ADA to our stake pool over a period of time. See how the extended loyalty bonuses can affect your final rewards.</p>
    </div>
</div>

<div class="row">

    <div class="col py-3">
        <div class="card p-3">

            <div class="row">
                <div class="col-8">
                    <p>Connect your wallet to check your reward balance.</p>
                </div>
                <div class="col">
                    <?php cpISPO()->template('part/delegate-connect'); ?>
                </div>
            </div>
        </div>

    </div>

</div>
<div class="row">
    <div class="col py-3">
        <div class="card p-3">
            <div class="row">
                <div class="col-8">
                    <label for="exampleFormControlInput1" class="form-label">Enter your wallet address</label>
                </div>


            </div>
            <div class="row">
                <div class="col-8">
                    <div class="input-group">
                        <input x-model="address" type="text" class="form-control" placeholder="Stake Address">
                        <button class="btn btn-primary" @click="handleTracking()" x-bind:disabled="!address">Track</button>
                    </div>
                </div>
                <div class="col">
                    <template x-if="isConnected">
                        <div class="mt-3">
                            <span class="h3 primary float-end">Rewards</span>
                            <input value="<?php echo number_format($rewards, 6); ?>" type="text" class="form-control" readonly disabled>
                        </div>
                    </template>


                </div>
            </div>


        </div>
    </div>

</div>

