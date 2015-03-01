<div class="content list">
    <div class="main overlay-on-ajax" id="snippet--content">
        <h1><?php echo $heading_title; ?></h1>
        <div class="filter">
            <ul>
                <li><label><input type="radio" name="filter" class="noreplace onChangeAjax"
                                  data-onchange-href="/c133233-maso-a-uzeniny?do=sort" checked=""><span
                                class="like-radio"></span>By popularity</label></li>
                <li><label><input type="radio" name="filter" class="noreplace onChangeAjax"
                                  data-onchange-href="/c133233-maso-a-uzeniny?orderBy=price&amp;desc=0&amp;do=sort"><span
                                class="like-radio"></span>From cheapest</label></li>
                <li class="checkbox"><label><input type="checkbox" name="filter" value="" class="noreplace onChangeAjax"
                                                   data-onchange-href="/c133233-maso-a-uzeniny?favourites=1&amp;do=toggleFavourites"><span
                                class="like-checkbox"></span>my favorite</label></li>
                <li class="checkbox"><label><input type="checkbox" name="filter" value="" class="noreplace onChangeAjax"
                                                   data-onchange-href="/c133233-maso-a-uzeniny?sales=1&amp;do=toggleSale"><span
                                class="like-checkbox"></span>Action goods</label></li>
            </ul>
        </div>

        <div class="infinitescroll" data-infinitescroll-item="article" data-infinitescroll-link=".loadMore">
            <div data-ajax-append="true" class="items" id="snippet--products">
                <?php if(!empty($products)): ?>
                <?php foreach($products as $product): ?>
                <article data-tags="[]" class="overlay-on-ajax product">
                    <a href="<?php echo $product['href']; ?>" class="Front:Category img gtmPushDetailOpen">
                        <img src="<?php echo $product['image']; ?>" class="grocery-image-placeholder"
                             data-replace="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">

                    </a>

                    <div id="snippet-product-1288579-basket">
                        <h3><a href=""
                               data-gtm-event="rohlik.productPopup"
                               data-popuplink=""
                               class="Front:Category gtmPushDetailOpen"><?php echo $product['name']; ?></a></h3>


                        <div>
                            <h6><strong><?php echo $product['price']; ?></span></h6>

                            <form data-gtm-event="{&quot;event&quot;:&quot;rohlik.basketAdd&quot;,&quot;basket.source&quot;:&quot;:Front:Category:default-product&quot;,&quot;basket.product.title&quot;:&quot;Dušená šunka z kýty Naše Miroslav - poctivá šunka nejvyšší jakosti 100g&quot;}"
                                  class="Front:Category ajax basket-form gtmPushProductFormSubmit"
                                  action="/c133233-maso-a-uzeniny" method="post" novalidate=""
                                  id="frm-product-1288579-basketForm" data-name="product-1288579-basketForm">
                                <p>
                                    <a href="javascript:;" class="remove decreasePieces"
                                       rel="#pieces-product-1288579-1288579">-</a>
                                    <input autocomplete="off" id="pieces-product-1288579-1288579" data-max-amount="97"
                                           type="text" name="amount" value="1" data-basket-amount="0"
                                           class="maxAmountValidate">
                                    <a href="javascript:;" class="add increasePieces"
                                       rel="#pieces-product-1288579-1288579">+</a>
                                    <button type="submit" name="add" id="frm-product-1288579-basketForm-add">Add to
                                        cart
                                    </button>
                                </p>
                                <div><input type="hidden" name="do" value="product-1288579-basketForm-submit">
                                    <!--[if IE]><input type=IEbug disabled style="display:none"><![endif]--></div>
                            </form>

                            <div class="ico">

					<span class="nomgr" id="snippet-product-1288579-favourite">
							<a class="ajax heart" title="Add to Favourites"
                               href="/c133233-maso-a-uzeniny?do=product-1288579-favourite">♥</a>
					</span>

                            </div>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="pages overlay-on-ajax" id="snippet-paginator-loadMore">
                <a class="loadMore ajax" href="/c133233-maso-a-uzeniny?do=paginator-loadMore">
                    Show more products
                </a>
            </div>
        </div>
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
        <a title="Get up to CZK 2000" href="/doporuc-a-ziskej">Get up to CZK 2000</a>
    </div>
</div>


<div class="container">
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
            <h2><?php echo $heading_title; ?></h2>
            <?php if ($thumb || $description) { ?>
            <div class="row">
                <?php if ($thumb) { ?>
                <div class="col-sm-2"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>"
                                           title="<?php echo $heading_title; ?>" class="img-thumbnail"/></div>
                <?php } ?>
                <?php if ($description) { ?>
                <div class="col-sm-10"><?php echo $description; ?></div>
                <?php } ?>
            </div>
            <hr>
            <?php } ?>
            <?php if ($categories) { ?>
            <h3><?php echo $text_refine; ?></h3>
            <?php if (count($categories) <= 5) { ?>
            <div class="row">
                <div class="col-sm-3">
                    <ul>
                        <?php foreach ($categories as $category) { ?>
                        <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php } else { ?>
            <div class="row">
                <?php foreach (array_chunk($categories, ceil(count($categories) / 4)) as $categories) { ?>
                <div class="col-sm-3">
                    <ul>
                        <?php foreach ($categories as $category) { ?>
                        <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
            <?php } ?>
            <?php if ($products) { ?>
            <p><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></p>

            <div class="row">
                <div class="col-md-4">
                    <div class="btn-group hidden-xs">
                        <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip"
                                title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
                        <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip"
                                title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
                    </div>
                </div>
                <div class="col-md-2 text-right">
                    <label class="control-label" for="input-sort"><?php echo $text_sort; ?></label>
                </div>
                <div class="col-md-3 text-right">
                    <select id="input-sort" class="form-control" onchange="location = this.value;">
                        <?php foreach ($sorts as $sorts) { ?>
                        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                        <option value="<?php echo $sorts['href']; ?>"
                                selected="selected"><?php echo $sorts['text']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-1 text-right">
                    <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
                </div>
                <div class="col-md-2 text-right">
                    <select id="input-limit" class="form-control" onchange="location = this.value;">
                        <?php foreach ($limits as $limits) { ?>
                        <?php if ($limits['value'] == $limit) { ?>
                        <option value="<?php echo $limits['href']; ?>"
                                selected="selected"><?php echo $limits['text']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <br/>

            <div class="row">
                <?php foreach ($products as $product) { ?>
                <div class="product-layout product-list col-xs-12">
                    <div class="product-thumb">
                        <div class="image"><a href="<?php echo $product['href']; ?>"><img
                                        src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"
                                        title="<?php echo $product['name']; ?>" class="img-responsive"/></a></div>
                        <div>
                            <div class="caption">
                                <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>

                                <p><?php echo $product['description']; ?></p>
                                <?php if ($product['rating']) { ?>
                                <div class="rating">
                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                    <?php if ($product['rating'] < $i) { ?>
                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                                    <?php } else { ?>
                                    <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i
                                                class="fa fa-star-o fa-stack-2x"></i></span>
                                    <?php } ?>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                                <?php if ($product['price']) { ?>
                                <p class="price">
                                    <?php if (!$product['special']) { ?>
                                    <?php echo $product['price']; ?>
                                    <?php } else { ?>
                                    <span class="price-new"><?php echo $product['special']; ?></span> <span
                                            class="price-old"><?php echo $product['price']; ?></span>
                                    <?php } ?>
                                    <?php if ($product['tax']) { ?>
                                    <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                                    <?php } ?>
                                </p>
                                <?php } ?>
                            </div>
                            <div class="button-group">
                                <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i
                                            class="fa fa-shopping-cart"></i> <span
                                            class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span>
                                </button>
                                <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>"
                                        onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i
                                            class="fa fa-heart"></i></button>
                                <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>"
                                        onclick="compare.add('<?php echo $product['product_id']; ?>');"><i
                                            class="fa fa-exchange"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                <div class="col-sm-6 text-right"><?php echo $results; ?></div>
            </div>
            <?php } ?>
            <?php if (!$categories && !$products) { ?>
            <p><?php echo $text_empty; ?></p>

            <div class="buttons">
                <div class="pull-right"><a href="<?php echo $continue; ?>"
                                           class="btn btn-primary"><?php echo $button_continue; ?></a></div>
            </div>
            <?php } ?>
            <?php echo $content_bottom; ?></div>
        <?php echo $column_right; ?></div>
</div>
