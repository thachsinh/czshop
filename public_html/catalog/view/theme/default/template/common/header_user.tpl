<!-- user login/logout -->
<?php if (!$logged) : ?>
<ul id="snippet--user-panel" class="user">
    <li class="arrow">
        <a class="xregistration" href="<?php echo $register; ?>">Register</a>
        <a class="xlogin" href="<?php echo $login; ?>">Log in</a>
    </li>
    <li id="snippet--userAddressPanel">
        <span class="change-wrapper">
            <a rel="lightbox-address-required" class="openLightbox" title="Změnit" href="javascript:;">
                Set delivery address
            </a>
        </span>
    </li>
</ul>
<?php else: ?>
<ul id="snippet--user-panel" class="user">
    <li class="arrow dropdown big">
        <a title="Profile" href="<?php echo $account; ?>">
            <font><font><?php echo $firstname; ?></font></font>
        </a>

        <ul>
            <li class="profile">
                <a title="<?php echo $text_account; ?>" href="<?php echo $account; ?>">
                    <font><font><?php echo $text_account; ?></font></font>
                </a>
            </li>
            <li class="logout">
                <a title="<?php echo $text_logout; ?>" href="<?php echo $logout; ?>">
                    <font><font><?php echo $text_logout; ?></font></font>
                </a>
            </li>
        </ul>
    </li>
    <li id="snippet--userAddressPanel">
        <span class="change-wrapper">
            <a href="javascript:;" title="" class="openLightbox" rel="lightbox-address-required">
                <?php echo $text_address_book; ?>
            </a>
        </span>
    </li>
</ul>
<?php endif; ?>