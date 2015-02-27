<aside>
    <h3><span><a href="<?php echo $this->url->link('product/category', 'path=' . $parent_category['category_id']); ?>"><?php echo $parent_category['name']; ?></a></span></h3>
    <?php if(!empty($sub_categories)): ?>
    <ul>
        <li>
            <ul>
                <?php foreach($sub_categories as $item): ?>
                <li><a href="<?php echo $this->url->link('product/category', 'path=' . $item['category_id']); ?>" <?php echo ($item['category_id'] == $active_category['category_id'])?'class="active"':''; ?>><?php echo $item['name']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
    <?php endif;?>
</aside>