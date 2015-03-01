<div class="about">
    <div class="overlay-on-ajax">
        <div id="snippet--editing">
            <dl>
                <dt><font><font class=""><?php echo $entry_firstname; ?></font></font></dt>
                <dd class="name"><font><font><?php echo $customer['firstname']; ?></font></font></dd>
                <dt><font><font class=""><?php echo $entry_lastname; ?></font></font></dt>
                <dd class="name"><font><font><?php echo $customer['lastname']; ?></font></font></dd>
                <dt><font><font><?php echo $entry_telephone; ?></font></font></dt>
                <dd><font><font><?php echo $customer['telephone']; ?></font></font></dd>
                <dt><font><font><?php echo $entry_email; ?></font></font></dt>
                <dd><font><font><?php echo $customer['email']; ?></font></font></dd>
            </dl>
        </div>
        <div id="snippet--changePassword"></div>
        <p>
            <a class="ajax" href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a><br/>
            <a class="ajax" href="<?php echo $password; ?>"><?php echo $text_password; ?></a><br/>
            <a class="ajax" href="<?php echo $address; ?>"><?php echo $text_address; ?></a><br/>
            <a class="ajax" href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a><br/>
        </p>
    </div>
    <div class="overlay-on-ajax billingInfo" id="snippet--changeBillingInfo">
        <h2><?php echo $text_my_orders; ?></h2>
        <p style="clear: both; margin-top: 0; padding-top: 0;">
            <a href="<?php echo $order; ?>"><?php echo $text_order; ?></a><br/>
            <a href="<?php echo $download; ?>"><?php echo $text_download; ?></a><br/>
            <?php if ($reward) { ?>
                <a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a><br/>
            <?php } ?>
            <a href="<?php echo $return; ?>"><?php echo $text_return; ?></a><br/>
            <a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a><br/>
            <a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a><br/>
        </p>
        <h2><?php echo $text_my_newsletter; ?></h2>
        <p style="clear: both; margin-top: 0; padding-top: 0;">
            <a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a>
        </p>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="static/css/account.css">