<div class="content detail open">
    <div class="main">
        <article>
            <h1><?php echo $heading_title; ?></h1>
            <span class="img">
                <img src="<?php echo $thumb; ?>" class="grocery-image-placeholder"  data-replace="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>">
                <span class="discount">-22%</span>
            </span>
            <div class="col">
                <div id="snippet-product-<?php echo $product_id;?>-basket" class="overlay-on-ajax">
                    <form data-name="product-<?php echo $product_id;?>-basketForm" id="frm-product-<?php echo $product_id;?>-basketForm" novalidate=""
                          method="post" action="<?php echo $action; ?>"
                          data-gtm-event="{'event': 'rohlik.basketAdd', 'basket.source':':Front:Product:default-product', 'basket.product.title':'Dušená šunka z kýty Naše Miroslav - poctivá šunka nejvyšší jakosti 100g'}" class="ajax">
                        <ul class="buy">
                            <li class="price">
                                <span>USD</span>
                                <strong class="unitPrice">(Unit Price)</strong>
                                <?php echo $price; ?>
                            </li>

                            <li><a rel="#pieces-product-<?php echo $product_id, '-', $product_id;?>" class="amount remove decreasePieces" href="javascript:;">−</a>
                                <input type="text" class="maxAmountValidate" data-basket-amount="0" value="<?php echo $minimum; ?>" name="quantity"
                                       data-max-amount="<?php echo $quantity; ?>" id="pieces-product-<?php echo $product_id, '-', $product_id;?>" autocomplete="off">
                                <a rel="#pieces-product-<?php echo $product_id, '-', $product_id;?>" class="amount add increasePieces" href="javascript:;">+</a>
                            </li>
                            <li>
                                <button id="frm-product-<?php echo $product_id;?>-basketForm-add" name="add" type="submit" title="<?php echo $lang['button_cart']; ?>">
                                    <?php echo $lang['button_cart']; ?>
                                </button>
                            </li>
                            
                            <li class="alert" style="clear:both;">Expiration Date: <?php echo $product['date_available']; ?></li>
                        </ul>
                        <div>
                            <input type="hidden" value="<?php echo $product_id; ?>" name="product_id" />
                            <input type="hidden" value="product-<?php echo $product_id;?>-basketForm-submit" name="do">
                            <!--[if IE]><input type=IEbug disabled style="display:none"><![endif]-->
                        </div>
                    </form>
                </div>

                <div class="like">
                    <div>
                        <a href="#" onclick="wishlist.add('<?php echo $product_id; ?>');"><b>♥ </b> <span><?php echo $lang['button_wishlist']; ?></span></a>
                    </div>
                    <br>
                    <div class="fb-like" data-href="" data-width="400" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
                </div>
                <ul class="info">
                    <li class="code"><?php echo $lang['text_sku']; ?> <strong><?php echo $sku; ?></strong></li>
                </ul>
            </div>
        </article>
        <section>
                <h6><?php echo $lang['tab_description']; ?></h6>
                <?php echo str_replace('div>', 'p>', $description); ?>
        </section>
    </div>

    <?php if(isset($left_menu)) echo $left_menu; ?>
    <?php echo $cart; ?>

    <div class="badge">
        <a title="Get up to CZK 2000" href="#">Get up to CZK 2000</a>
    </div>
</div>
