<?php if(!empty($breadcrumbs)): ?>
<div class="breadcrumb <?php echo isset($breadcrumbs_class)?$breadcrumbs_class:''; ?>">
    <div class="container">
        <ol>
            <?php foreach($breadcrumbs as $item): ?>
            <?php if(isset($item['href'])): ?>
            <li><a title="<?php echo $item['text']; ?>" href="<?php echo $item['href']; ?>"><?php echo $item['text'];?></a></li>
            <?php else: ?>
            <li><?php echo $item['text']; ?></li>
            <?php endif;?>
            <?php endforeach; ?>
        </ol>
    </div>
</div>
<?php endif; ?>