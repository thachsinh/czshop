<div class="content profile hiddenCart">
    <div class="main">
        <h1><?php echo $text_address_book; ?></h1>
        <div class="archive">
            <div style="clear: both;"></div>
            <?php if ($addresses) { ?>
                <div id="snippet-ordersHistoryGrocery-browseOrders">
                    <?php foreach ($addresses as $result) :?>
                    <ul>
                        <li class="address"><strong><font><font class=""><?php echo $result['address']; ?></font></font></strong></li>
                        <li class="detail"><a href="<?php echo $result['update']; ?>"><font><font><?php echo $button_edit; ?></font></font></a></li>
                        <li class="detail"><a href="<?php echo $result['delete']; ?>"><font><font><?php echo $button_delete; ?></font></font></a></li>
                    </ul>
                    <?php endforeach; ?>
                </div>
            <?php } else { ?>
                <p><?php echo $text_empty; ?></p>
            <?php } ?>
            <div class="buttons clearfix">
                <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
                <div class="pull-right"><a href="<?php echo $add; ?>" class="btn btn-primary"><?php echo $button_new_address; ?></a></div>
            </div>
        </div>
        <?php echo $account_menu; ?>
    </div>
</div>

