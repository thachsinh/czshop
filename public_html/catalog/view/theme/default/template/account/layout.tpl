<?php echo $header; ?>

<link rel="stylesheet" type="text/css" href="static/css/account.css">

<div class="breadcrumb">
    <div class="container">
        <ol>
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
        </ol>
    </div>
</div>

<?php
if (!isset($success)) $success = '';
if (!isset($error_warning)) $error_warning = '';
if ($success || $error_warning): ?>
<div id="snippet--flashMessages">
    <div class="flashes">
        <div class="flash">
            <?php if ($success) { ?>
            <p class="success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></p>
            <?php } ?>
            <?php if ($error_warning) { ?>
            <p class="error"><?php echo $error_warning; ?></p>
            <?php } ?>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="content profile hiddenCart">
    <div class="main">
        <h1><?php echo $heading_title; ?></h1>
        <div class="archive"></div>
        <?php echo $account_menu; ?>
        <div class="archive main-content">
            <div class="about">
                <div class="overlay-on-ajax billingInfo" id="snippet--changeBillingInfo">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $footer; ?>