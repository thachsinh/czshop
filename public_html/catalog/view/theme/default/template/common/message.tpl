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