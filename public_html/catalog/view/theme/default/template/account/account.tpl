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
<div class="content profile hiddenCart">
    <div class="main">
        <h1><?php echo $text_my_account; ?></h1>
        <div class="archive"></div>
        <?php echo $account_menu; ?>
        <div class="main-content">
            <?php echo $content; ?>
        </div>
    </div>
</div>
<?php echo $footer; ?>