<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
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