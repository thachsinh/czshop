<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
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
        <?php echo $menu; ?>
        <!-- END SIDEBPANEL-->
        <!-- START PAGE-CONTAINER -->
        <div class="page-container">
            <!-- START HEADER -->
            <?php echo $header; ?>
            <!-- END HEADER -->
            <!-- START PAGE CONTENT WRAPPER -->
            <div class="page-content-wrapper">
                <!-- START PAGE CONTENT -->
                <div class="content">
                    <?php echo $main_content; ?>
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
