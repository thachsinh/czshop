<h2><?php echo $text_password; ?></h2>
<div style="clear: both;"></div>
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