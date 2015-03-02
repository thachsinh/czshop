<div id="snippet-cart-cart">
    <form data-name="cart-form" id="frm-cart-form" novalidate="" method="post" action="/">
        <div class="basket productsInBasket overlay-on-ajax">
            <div class="header">
                <h6>
                    Cart
                    <span><?php echo $items_amount; ?></span>
                </h6>
                <b><?php echo $total; ?>&nbsp;$</b>
            </div>
            <div class="items ps-container" style="height: 388px;">
                <?php foreach ($products as $product) : ?>
                <article class="product">
                    <a class="Front:Category img gtmPushDetailOpen" href="/1289721-cibule-zluta-1ks"
                       data-popuplink="/c75403-cerstve-potraviny?productId=1289721&do=openProductPopup">
                        <img alt="" data-replace="/images/grocery/products/8791/71/1289721-1423844924-500.jpg"
                             class="grocery-image-placeholder" src="<?php echo $product['thumb']; ?>" />
                    </a>
                    <h3>
                        <a href="/1289721-cibule-zluta-1ks" data-popuplink="/c75403-cerstve-potraviny?productId=1289721&do=openProductPopup"
                           class="Front:Category gtmPushDetailOpen"><?php echo $product['name']; ?></a>
                    </h3>
                    <h6><strong><?php echo $product['total']; ?>&nbsp;$</strong></h6>

                    <p>
                        <a href="/c75403-cerstve-potraviny?cart-item=1008959345&amp;do=cart-removeItem" class="ajax">−</a>
                        1×
                        <a href="/c75403-cerstve-potraviny?cart-item=1008959345&amp;do=cart-copyItem" class="ajax">+</a>
                    </p>

                    <a href="/c75403-cerstve-potraviny?cart-item=1008959345&amp;do=cart-clearItem" class="ajax remove" title="Odstranit">×</a>
                </article>
                <?php endforeach; ?>
                <div class="ps-scrollbar-x-rail" style="width: 258px; display: none; left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; height: 478px; display: none; right: 3px;"><div class="ps-scrollbar-y" style="top: 0px; height: 0px;"></div></div>
            </div>
            <div class="footer">
                <div class="sum">
                    <i>79&nbsp;Kč</i>
                    <b>Celkem <span>113&nbsp;Kč</span></b>
                </div>

                <div class="deliveryUpsell">							<a class="registration" href="javascript:;">Získat 100&nbsp;Kč na&nbsp;nákup&nbsp;a dopravu zdarma</a>
                </div>
                <p>
                    Minimální objednávka je 500&nbsp;Kč.<br>Vyberte si ještě zboží za alespoň <strong>466&nbsp;Kč</strong>.
                </p>
            </div>
        </div>

        <div><input type="hidden" value="cart-form-submit" name="do"><!--[if IE]><input type=IEbug disabled style="display:none"><![endif]--></div>
    </form>
</div>