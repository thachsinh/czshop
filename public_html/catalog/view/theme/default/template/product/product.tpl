<div class="content detail open">
    <div class="main">
        <article>
            <h1><?php echo $heading_title; ?></h1>
            <span class="img">
                <img src="<?php echo $thumb; ?>" class="grocery-image-placeholder"  data-replace="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>">
                <span class="discount">-22%</span>
            </span>
            <div class="col">
                <div class="overlay-on-ajax">
                        <ul class="buy expire">
                            <li class="price">
                                <span></span>
                                <strong class="unitPrice"><?php echo $price; ?></strong></li>
                            <li>
                                <a href="javascript:;" class="amount remove decreasePieces">-</a>
                                <input data-max-amount="103" type="text" name="amount" value="1">
                                <a class="amount add increasePieces">+</a>
                            </li>
                            <li>
                                <button title="Add to cart" type="submit" name="add"><?php echo $lang['button_cart']; ?></button>
                            </li>
                            <li class="alert">Expiration Date January 28, 2015</li>
                        </ul>
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
            <div>
                <h6><?php echo $lang['tab_description']; ?></h6>
                <?php echo $description; ?>
            </div>
            <div>
                <h6><?php echo $lang['tab_description']; ?></h6>
                <?php echo $description; ?>
            </div>
        </section>
    </div>

    <?php if(isset($left_menu)) echo $left_menu; ?>

    <div id="snippet-cart-cart">
        <div class="basket overlay-on-ajax">
            <div class="header">
                <h6>Your basket is empty.</h6>
            </div>
        </div>
    </div>
    <div class="badge">
        <a title="Get up to CZK 2000" href="#">Get up to CZK 2000</a>
    </div>
</div>
