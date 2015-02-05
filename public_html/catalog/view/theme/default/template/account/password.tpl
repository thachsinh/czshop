<?php echo $header; ?>

<div class="breadcrumb">
    <div class="container">
        <ol>
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ol>
    </div>
</div>
<?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<div class="content profile hiddenCart">
    <div class="main">
        <h1><?php echo $heading_title; ?></h1>
        <?php echo $account_menu; ?>
        <div class="archive">
            <div class="about">
                <div class="overlay-on-ajax billingInfo" id="snippet--changeBillingInfo">
                    <h2><?php echo $text_password; ?></h2>
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <fieldset>
                            <div class="form-group required row">
                                <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
                                <div class="col-sm-10">
                                    <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                                    <?php if ($error_password) { ?>
                                        <div class="text-danger"><?php echo $error_password; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required row">
                                <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
                                <div class="col-sm-10">
                                    <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
                                    <?php if ($error_confirm) { ?>
                                        <div class="text-danger"><?php echo $error_confirm; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </fieldset>
                        <div class="buttons clearfix">
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary"><?php echo $button_continue; ?></button>
                            </div>
                            <p class="back"><a class="ajax" href="<?php echo $back; ?>"><font><font class="goog-text-highlight"><?php echo $button_dont_change; ?></font></font></a></p>
                        </div>
                    </form>
                    <?php echo $content_bottom; ?>
                    <?php echo $column_right; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $footer; ?>