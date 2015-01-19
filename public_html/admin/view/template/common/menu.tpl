<ul class="nav navbar-nav">
  <li id="sale" class="dropdown">
    <a><i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_sale; ?> <b class="caret"></b></span></a>
    <ul class="dropdown-menu">
      <li class="dropdown-header"><?php echo $text_order; ?></li>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <li><a href="<?php echo $order_recurring; ?>"><?php echo $text_order_recurring; ?></a></li>
      <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
    </ul>
  </li>
  <li id="sale" class="dropdown">
    <a><i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_customer; ?> <b class="caret"></b></span></a>
    <ul class="dropdown-menu">
      <li class="dropdown-header"><?php echo $text_customer; ?></li>
      <li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
      <li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>
      <li><a href="<?php echo $custom_field; ?>"><?php echo $text_custom_field; ?></a></li>
      <li><a href="<?php echo $customer_ban_ip; ?>"><?php echo $text_customer_ban_ip; ?></a></li>
    </ul>
  </li>
  <li id="catalog" class="dropdown">
    <a><i class="fa fa-tags fa-fw"></i> <span><?php echo $text_catalog; ?> <b class="caret"></b></span></a>
    <ul class="dropdown-menu">
      <li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>
      <li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>
      <li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
      <li><a href="<?php echo $filter; ?>"><?php echo $text_filter; ?></a></li>
      <li><a href="<?php echo $option; ?>"><?php echo $text_option; ?></a></li>
      <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
      <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
      <li><a href="<?php echo $review; ?>"><?php echo $text_review; ?></a></li>
      <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
    </ul>
  </li>
  <li id="system" class="dropdown">
    <a><i class="fa fa-cog fa-fw"></i> <span><?php echo $text_system; ?> <b class="caret"></b></span></a>
    <ul class="dropdown-menu">
      <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
      <li class="divider"></li>
      <li class="dropdown-header"><?php echo $text_user; ?></li>
      <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
      <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
      <li class="divider"></li>
      <li class="dropdown-header"><?php echo $text_localisation; ?></li>
      <li><a href="<?php echo $location; ?>"><?php echo $text_location; ?></a></li>
      <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
      <li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
      <li><a href="<?php echo $stock_status; ?>"><?php echo $text_stock_status; ?></a></li>
      <li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li>
      <li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>
      <li><a href="<?php echo $zone; ?>"><?php echo $text_zone; ?></a></li>
      <li><a href="<?php echo $geo_zone; ?>"><?php echo $text_geo_zone; ?></a></li>
    </ul>
  </li>
  <li id="system" class="dropdown">
    <a><i class="fa fa-share-alt fa-fw"></i> <span><?php echo $text_marketing; ?> <b class="caret"></b></span></a>
    <ul class="dropdown-menu">
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
    </ul>
  </li>
  <li id="system" class="dropdown">
    <a><i class="fa fa-bar-chart-o fa-fw"></i> <span><?php echo $text_reports; ?> <b class="caret"></b></span></a>
    <ul class="dropdown-menu">
      <li><a href="<?php echo $report_customer_online; ?>"><?php echo $text_report_customer_online; ?></a></li>
      <li><a href="<?php echo $report_customer_activity; ?>"><?php echo $text_report_customer_activity; ?></a></li>
      <li><a href="<?php echo $report_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>
      <li><a href="<?php echo $report_customer_reward; ?>"><?php echo $text_report_customer_reward; ?></a></li>
    </ul>
  </li>
</ul>
