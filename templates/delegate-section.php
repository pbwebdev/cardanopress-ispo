<?php

/**
 * The template for displaying the delegate section.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/delegate-section.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

$pool = cpISPO()->delegationPool();

?>

<div class="row">
    <div class="col text-center col-md-8 mx-auto">
        <h2>Pool Stats & Delegate Your ADA</h2>
        <p>Check the pool and ensure that you're delegating to the correct and official one. Search for the pool ticker <strong>GOKEY</strong>, or search using the Pool ID. Ensure you are delegating to the correct stake pool.</p>
        <p>Pool Id: pool1c0p84nykx0m4yqs9pkvlgagh3nlnrn77d5zed9kv9ktljh2cjcy</p>
        <span class="fa-solid fa-copy">Copy</span>  <span class="fa-solid fa-up-right-from-square">View</span>
    </div>
</div>

<div class="row m-3">
    <!-- Widget Type 1-->
    <div class="mb-4 col">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-normal text-green">357,261</h4>
                        <p class="subtitle text-sm text-muted mb-0">Active Stake</p>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="mb-4 col">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-normal text-green">102</h4>
                        <p class="subtitle text-sm text-muted mb-0">Delegates</p>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="mb-4 col">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-normal text-green">1</h4>
                        <p class="subtitle text-sm text-muted mb-0">Blocks Minted</p>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

<div class="text-center">
    <h3>Start Delegating</h3>
    <p>Using a Web3 wallet with a DApp connector such as Nami, Eternl or Typhon? Not delegated yet? Simply delegate to GoKey in two steps. Connect your wallet, then click Delegate to sign the transaction.</p>
    <?php //cardanoPress()->template('part/delegation-details', compact('pool')); ?>
    <div class="py-3">
        <h2>Step 1</h2>
        <?php cpISPO()->template('part/delegate-connect'); ?>
    </div>
    <div class="bg-primary mx-auto" style="width: 2px; height: 3em;"></div>
    <div class="py-3">
        <h2>Step 2</h2>
        <?php cpISPO()->template('part/delegate-process'); ?>
    </div>
    <div class="py-3">
        <?php cpISPO()->template('part/delegate-result'); ?>
    </div>
</div>
