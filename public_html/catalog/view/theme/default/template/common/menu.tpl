<nav>
    <?php if(!empty($categories)): ?>
    <ul>
        <li><a href="#" title=""><span class="fav">&hearts;</span></a></li>
        <li><a href="#" title="Akční zboží"><span class="disc">Akce</span></a></li>
        <?php foreach($categories as $item): ?>
        <li><a href="<?php echo $item['link']; ?>"> <?php echo $item['name']; ?><i></i>
                <div class="clr"></div>
            </a>
            <?php if(isset($item['child'])): ?>
            <div class="category">
                <ul>
                    <?php foreach($item['child'] as $subItem): ?>
                    <li><a href="<?php echo $subItem['link']; ?>"><?php echo $subItem['name']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</nav>