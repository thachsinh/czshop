<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="apple-touch-icon" href="<?php echo $this->config->base_url(); ?>static/pages/ico/60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $this->config->base_url(); ?>static/pages/ico/76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $this->config->base_url(); ?>static/pages/ico/120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $this->config->base_url(); ?>static/pages/ico/152.png">
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="<?php echo $this->config->base_url(); ?>static/assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->config->base_url(); ?>static/assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->config->base_url(); ?>static/assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->config->base_url(); ?>static/assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo $this->config->base_url(); ?>static/assets/plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo $this->config->base_url(); ?>static/assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo $this->config->base_url(); ?>static/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->config->base_url(); ?>static/assets/plugins/bootstrap-tag/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->config->base_url(); ?>static/assets/plugins/dropzone/css/dropzone.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->config->base_url(); ?>static/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
    <link href="<?php echo $this->config->base_url(); ?>static/assets/plugins/summernote/css/summernote.css" rel="stylesheet" type="text/css" media="screen">
    <link href="<?php echo $this->config->base_url(); ?>static/pages/css/pages-icons.css" rel="stylesheet" type="text/css">
    <link class="main-stylesheet" href="<?php echo $this->config->base_url(); ?>static/pages/css/pages.css" rel="stylesheet" type="text/css" />
    <!--[if lte IE 9]>
        <link href="<?php echo $this->config->base_url(); ?>static/pages/css/ie9.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <script type="text/javascript">
    window.onload = function()
    {
      // fix for windows 8
      if (navigator.appVersion.indexOf("Windows NT 6.2") != -1)
        document.head.innerHTML += '<link rel="stylesheet" type="text/css" href="<?php echo $this->config->base_url(); ?>static/pages/css/windows.chrome.fix.css" />'
    }
    </script>
  </head>
  <body class="fixed-header   ">
    <!-- BEGIN SIDEBPANEL-->
    <nav class="page-sidebar" data-pages="sidebar">
      <!-- BEGIN SIDEBAR MENU TOP TRAY CONTENT-->
      <div class="sidebar-overlay-slide from-top" id="appMenu">
        <div class="row">
          <div class="col-xs-6 no-padding">
            <a href="#" class="p-l-40"><img src="<?php echo $this->config->base_url(); ?>static/assets/img/demo/social_app.svg" alt="socail">
            </a>
          </div>
          <div class="col-xs-6 no-padding">
            <a href="#" class="p-l-10"><img src="<?php echo $this->config->base_url(); ?>static/assets/img/demo/email_app.svg" alt="socail">
            </a>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-6 m-t-20 no-padding">
            <a href="#" class="p-l-40"><img src="<?php echo $this->config->base_url(); ?>static/assets/img/demo/calendar_app.svg" alt="socail">
            </a>
          </div>
          <div class="col-xs-6 m-t-20 no-padding">
            <a href="#" class="p-l-10"><img src="<?php echo $this->config->base_url(); ?>static/assets/img/demo/add_more.svg" alt="socail">
            </a>
          </div>
        </div>
      </div>
      <!-- END SIDEBAR MENU TOP TRAY CONTENT-->
      <!-- BEGIN SIDEBAR MENU HEADER-->
      <div class="sidebar-header">
        <img src="<?php echo $this->config->base_url(); ?>static/assets/img/logo_white.png" alt="logo" class="brand" data-src="<?php echo $this->config->base_url(); ?>static/assets/img/logo_white.png" data-src-retina="<?php echo $this->config->base_url(); ?>static/assets/img/logo_white_2x.png" width="78" height="22">
        <div class="sidebar-header-controls">
          <button type="button" class="btn btn-xs sidebar-slide-toggle btn-link m-l-20" data-pages-toggle="#appMenu"><i class="fa fa-angle-down fs-16"></i>
          </button>
          <button type="button" class="btn btn-link visible-lg-inline" data-toggle-pin="sidebar"><i class="fa fs-12"></i>
          </button>
        </div>
      </div>
      <!-- END SIDEBAR MENU HEADER-->
      <?php echo $menu; ?>
    </nav>
    <!-- END SIDEBAR -->
    <!-- END SIDEBPANEL-->
    <!-- START PAGE-CONTAINER -->
    <div class="page-container">
      <!-- START HEADER -->
      <div class="header ">
        <!-- START MOBILE CONTROLS -->
        <!-- LEFT SIDE -->
        <div class="pull-left full-height visible-sm visible-xs">
          <!-- START ACTION BAR -->
          <div class="sm-action-bar">
            <a href="#" class="btn-link toggle-sidebar" data-toggle="sidebar">
              <span class="icon-set menu-hambuger"></span>
            </a>
          </div>
          <!-- END ACTION BAR -->
        </div>
        <!-- RIGHT SIDE -->
        <div class="pull-right full-height visible-sm visible-xs">
          <!-- START ACTION BAR -->
          <div class="sm-action-bar">
            <a href="#" class="btn-link" data-toggle="quickview" data-toggle-element="#quickview">
              <span class="icon-set menu-hambuger-plus"></span>
            </a>
          </div>
          <!-- END ACTION BAR -->
        </div>
        <!-- END MOBILE CONTROLS -->
        <div class=" pull-left sm-table">
          <div class="header-inner">
            <div class="brand inline">
              <img src="<?php echo $this->config->base_url(); ?>static/assets/img/logo.png" alt="logo" data-src="<?php echo $this->config->base_url(); ?>static/assets/img/logo.png" data-src-retina="<?php echo $this->config->base_url(); ?>static/assets/img/logo_2x.png" width="78" height="22">
            </div>
            <!-- START NOTIFICATION LIST -->
            <ul class="notification-list no-margin hidden-sm hidden-xs b-grey b-l b-r no-style p-l-30 p-r-20">
              <li class="p-r-15 inline">
                <div class="dropdown">
                  <a href="javascript:;" id="notification-center" class="icon-set globe-fill" data-toggle="dropdown">
                    <span class="bubble"></span>
                  </a>
                  <!-- START Notification Dropdown -->
                  <div class="dropdown-menu notification-toggle" role="menu" aria-labelledby="notification-center">
                    <!-- START Notification -->
                    <div class="notification-panel">
                      <!-- START Notification Body-->
                      <div class="notification-body scrollable">
                        <!-- START Notification Item-->
                        <div class="notification-item unread clearfix">
                          <!-- START Notification Item-->
                          <div class="heading open">
                            <a href="#" class="text-complete">
                              <i class="pg-map fs-16 m-r-10"></i>
                              <span class="bold">Carrot Design</span>
                              <span class="fs-12 m-l-10">David Nester</span>
                            </a>
                            <div class="pull-right">
                              <div class="thumbnail-wrapper d16 circular inline m-t-15 m-r-10 toggle-more-details">
                                <div><i class="fa fa-angle-left"></i>
                                </div>
                              </div>
                              <span class=" time">few sec ago</span>
                            </div>
                            <div class="more-details">
                              <div class="more-details-inner">
                                <h5 class="semi-bold fs-16">“Apple’s Motivation - Innovation <br> 
                                                            distinguishes between <br>
                                                            A leader and a follower.”</h5>
                                <p class="small hint-text">
                                  Commented on john Smiths wall.
                                  <br> via pages framework.
                                </p>
                              </div>
                            </div>
                          </div>
                          <!-- END Notification Item-->
                          <!-- START Notification Item Right Side-->
                          <div class="option" data-toggle="tooltip" data-placement="left" title="mark as read">
                            <a href="#" class="mark"></a>
                          </div>
                          <!-- END Notification Item Right Side-->
                        </div>
                        <!-- START Notification Body-->
                        <!-- START Notification Item-->
                        <div class="notification-item  clearfix">
                          <div class="heading">
                            <a href="#" class="text-danger">
                              <i class="fa fa-exclamation-triangle m-r-10"></i>
                              <span class="bold">98% Server Load</span>
                              <span class="fs-12 m-l-10">Take Action</span>
                            </a>
                            <span class="pull-right time">2 mins ago</span>
                          </div>
                          <!-- START Notification Item Right Side-->
                          <div class="option">
                            <a href="#" class="mark"></a>
                          </div>
                          <!-- END Notification Item Right Side-->
                        </div>
                        <!-- END Notification Item-->
                        <!-- START Notification Item-->
                        <div class="notification-item  clearfix">
                          <div class="heading">
                            <a href="#" class="text-warning-dark">
                              <i class="fa fa-exclamation-triangle m-r-10"></i>
                              <span class="bold">Warning Notification</span>
                              <span class="fs-12 m-l-10">Buy Now</span>
                            </a>
                            <span class="pull-right time">yesterday</span>
                          </div>
                          <!-- START Notification Item Right Side-->
                          <div class="option">
                            <a href="#" class="mark"></a>
                          </div>
                          <!-- END Notification Item Right Side-->
                        </div>
                        <!-- END Notification Item-->
                        <!-- START Notification Item-->
                        <div class="notification-item unread clearfix">
                          <div class="heading">
                            <div class="thumbnail-wrapper d24 circular b-white m-r-5 b-a b-white m-t-10 m-r-10">
                              <img width="30" height="30" data-src-retina="<?php echo $this->config->base_url(); ?>static/assets/img/profiles/1x.jpg" data-src="<?php echo $this->config->base_url(); ?>static/assets/img/profiles/1.jpg" alt="" src="<?php echo $this->config->base_url(); ?>static/assets/img/profiles/1.jpg">
                            </div>
                            <a href="#" class="text-complete">
                              <span class="bold">Revox Design Labs</span>
                              <span class="fs-12 m-l-10">Owners</span>
                            </a>
                            <span class="pull-right time">11:00pm</span>
                          </div>
                          <!-- START Notification Item Right Side-->
                          <div class="option" data-toggle="tooltip" data-placement="left" title="mark as read">
                            <a href="#" class="mark"></a>
                          </div>
                          <!-- END Notification Item Right Side-->
                        </div>
                        <!-- END Notification Item-->
                      </div>
                      <!-- END Notification Body-->
                      <!-- START Notification Footer-->
                      <div class="notification-footer text-center">
                        <a href="#" class="">Read all notifications</a>
                        <a data-toggle="refresh" class="portlet-refresh text-black pull-right" href="#">
                          <i class="pg-refresh_new"></i>
                        </a>
                      </div>
                      <!-- START Notification Footer-->
                    </div>
                    <!-- END Notification -->
                  </div>
                  <!-- END Notification Dropdown -->
                </div>
              </li>
              <li class="p-r-15 inline">
                <a href="#" class="icon-set clip "></a>
              </li>
              <li class="p-r-15 inline">
                <a href="#" class="icon-set grid-box"></a>
              </li>
            </ul>
            <!-- END NOTIFICATIONS LIST -->
            <a href="#" class="search-link" data-toggle="search"><i class="pg-search"></i>Type anywhere to <span class="bold">search</span></a> </div>
        </div>
        <div class=" pull-right">
          <div class="header-inner">
            <a href="#" class="btn-link icon-set menu-hambuger-plus m-l-20 sm-no-margin hidden-sm hidden-xs" data-toggle="quickview" data-toggle-element="#quickview"></a>
          </div>
        </div>
        <div class=" pull-right">
          <!-- START User Info-->
          <div class="visible-lg visible-md m-t-10">
            <div class="pull-left p-r-10 p-t-10 fs-16 font-heading">
              <span class="semi-bold">David</span> <span class="text-master">Nest</span>
            </div>
            <div class="thumbnail-wrapper d32 circular inline m-t-5">
              <img src="<?php echo $this->config->base_url(); ?>static/assets/img/profiles/avatar.jpg" alt="" data-src="<?php echo $this->config->base_url(); ?>static/assets/img/profiles/avatar.jpg" data-src-retina="<?php echo $this->config->base_url(); ?>static/assets/img/profiles/avatar_small2x.jpg" width="32" height="32">
            </div>
          </div>
          <!-- END User Info-->
        </div>
      </div>
      <!-- END HEADER -->
      <!-- START PAGE CONTENT WRAPPER -->
      <div class="page-content-wrapper">
        <!-- START PAGE CONTENT -->
        <div class="content">
            <?php echo $main_content;?>
        </div>
        <!-- END PAGE CONTENT -->
        <?php echo $footer; ?>
      </div>
      <!-- END PAGE CONTENT WRAPPER -->
    </div>
    <!-- END PAGE CONTAINER -->
    <!-- BEGIN VENDOR JS -->
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/pace/pace.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/jquery/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/modernizr.custom.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/boostrapv3/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/jquery/jquery-easy.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/jquery-bez/jquery.bez.min.js"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/jquery-ios-list/jquery.ioslist.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/jquery-actual/jquery.actual.min.js"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>static/assets/plugins/bootstrap-select2/select2.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>static/assets/plugins/classie/classie.js"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>static/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>static/assets/plugins/dropzone/dropzone.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>static/assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>static/assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/boostrap-form-wizard/js/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
    <!-- END VENDOR JS -->
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="<?php echo $this->config->base_url(); ?>static/pages/js/pages.min.js"></script>
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="<?php echo $this->config->base_url(); ?>static/assets/js/form_elements.js" type="text/javascript"></script>
    <script src="<?php echo $this->config->base_url(); ?>static/assets/js/scripts.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS -->
  </body>
</html>
