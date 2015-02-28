<?php require_once 'footer_user.tpl'; ?>
<div class="overlay"></div>
<!-- Footer -->
<footer>
  <div class="container">
    <section class="contact">
      <ul>
        <li class="phone"><strong><span>+420 773 458 888</span></strong> <span>(Po - Pá&nbsp;10 - 23, So - Ne&nbsp;8.30 - 23)<span>
        </li>
        <li class="mail"><a href="mailto:tde@tamda.eu">tde@tamda.eu</a> <span>Na e-mail se snažíme odpovídat okamžitě</span>
        </li>
      </ul>
    </section>
    <div class="cols">
      <section class="custommers">
        <h5><?php echo $text_account; ?></h5>
        <ul>
          <li><a title="<?php echo $text_account; ?>" href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li><a title="<?php echo $text_order; ?>" href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a title="<?php echo $text_wishlist; ?>" href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          <li><a title="<?php echo $text_newsletter; ?>" href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>
      </section>
      <section class="about">
        <h5><?php echo $text_service; ?></h5>
        <ul>
          <li><a title="<?php echo $text_contact; ?>" href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          <li><a title="<?php echo $text_return; ?>" href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          <li><a title="<?php echo $text_sitemap; ?>" href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </section>

      <p><?php echo $powered; ?></p>

    </div>
    <section class="fb">
      <div class="fb-like-box" data-href="https://www.facebook.com/tamda.eu" data-colorscheme="light"
           data-width="292" data-height="180" data-show-faces="true" data-stream="false" data-header="false"
           data-show-border="false"></div>
    </section>
  </div>
</footer><!-- End Footer -->
<div class="tooltip"></div>
<div id="fb-root"></div>
<!--[if (gte ie 6)&(lte ie 8)]>
<script src="/js/selectivizr.js"></script>
<![endif]-->

<script>var dataLayer = [{
    "title": "Online supermarket Rohlik.cz",
    "pageType": ":Front:Homepage:default",
    "category": null,
    "minOrder": null,
    "addressStreet": "Jihlavská 823/78",
    "addressCity": "Praha",
    "visitorType": "new_customer",
    "ordersTotal": "2",
    "userId": 605653,
    "userEmail": "viet.prg@gmail.com",
    "userCreateDate": "2014-11-30 16:52",
    "userStatus": "bronze",
    "firstVisit": false,
    "breadcrumbs": []
  }];</script>
</body>
</html>

<!--<footer>
  <div class="container">
    <div class="row">
      <?php if ($informations) { ?>
      <div class="col-sm-3">
        <h5><?php echo $text_information; ?></h5>
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      <div class="col-sm-3">
        <h5><?php echo $text_service; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h5><?php echo $text_extra; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h5><?php echo $text_account; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>
      </div>
    </div>
    <hr>
    <p><?php echo $powered; ?></p> 
  </div>
</footer>

</body></html>-->