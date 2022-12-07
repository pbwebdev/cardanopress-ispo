<?php

/**
 * The template for displaying the delegate section.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/delegate-section.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

use PBWebDev\CardanoPress\ISPO\Actions;

$network = cpISPO()->userProfile()->connectedNetwork();
$link = Actions::getCardanoscanLink($network, 'transaction/');

?>

<div class="col-md-8 mx-auto text-center">
    <h3>Start Delegating</h3>
    <p>Connect your wallet, then click Delegate to sign the transaction.</p>

    <div class="py-3">
        <h2>Step 1</h2>

        <?php cpISPO()->template('part/delegate-connect'); ?>
    </div>

    <div class="bg-primary mx-auto" style="width: 2px; height: 3em;"></div>

    <div class="py-3">
        <h2>Step 2</h2>

        <?php cpISPO()->template('part/delegate-process'); ?>
    </div>

    <template x-if="isConnected && transactionHash">
        <div class="py-3">
            <h3>Delegation Result</h3>
            <p><a x-bind:href="'<?php echo esc_url($link); ?>' + transactionHash" target="_blank">View transaction</a> on CardanoScan.</p>
        </div>
    </template>

    <?php cpISPO()->template('part/delegate-result'); ?>
</div>
