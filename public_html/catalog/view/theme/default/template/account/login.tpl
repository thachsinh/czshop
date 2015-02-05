<?php echo $header; ?>
<div class="container">
    <!--<ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>-->

    <div class="loginRequired">
        <?php if ($success || $error_warning): ?>
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

        <div class="left">
            <h2><?php echo $text_new_customer; ?></h2>
            <p><?php echo $text_register; ?></p>
            <p><?php echo $text_register_account; ?></p>
            <a class="xregistration xopenRegisterPopup" href="<?php echo $register; ?>"><?php echo $button_continue; ?></a>
        </div>

        <div class="right">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" novalidate="" id="frm-loginForm" data-name="loginForm">
                <a href="javascript:;" class="fb" rel="nofollow">
                    <?php echo $text_returning_customer; ?>
                </a>

                <p class="or"><?php echo $text_i_am_returning_customer; ?></p>

                <label for="frm-loginForm-email">
                    <input type="email" name="email" placeholder="<?php echo $entry_email; ?>" value="<?php echo $email; ?>"
                           title="Your e-mail" id="frm-loginForm-email" required="" class="txt user" /></label>
                <label for="frm-loginForm-password">
                    <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>"
                           title="Password" id="frm-loginForm-password" required=""
                           class="txt password"></label>
                <p class="login-btn">
                    <a href="javascript:;" class="openLostPasswordPopup" rel="forgottenPassword-opener"><?php echo $text_forgotten; ?></a>
                    <button type="submit"><?php echo $button_login; ?></button>
                </p>
                <?php if ($redirect) { ?>
                <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                <?php } ?>
                <div><input type="hidden" name="do" value="loginForm-submit"></div>
            </form>
        </div>
    </div>
</div>
<?php echo $footer; ?>