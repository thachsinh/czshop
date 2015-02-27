<!DOCTYPE html>
<html class="no-js " lang="cs">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# tamda.eu: http://ogp.me/ns/fb/tamda.eu#">
    <meta charset="utf-8">
    <script type="text/javascript">try {
        } catch (err) {
            console.log(err);
        }</script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo (null !== $this->document->getTitle()) ? $this->document->getTitle() : ''; ?></title>
    <?php if($this->document->getDescription()): ?>
    <meta name="description" content="<?php echo $this->document->getDescription(); ?>">
    <?php endif; ?>
    <?php if ($this->document->getKeywords()): ?>
    <meta name="keywords" content="<?php echo $this->document->getKeywords(); ?>"/>
    <?php endif; ?>
    <?php if (isset($icon)): ?>
    <link href="<?php echo $icon; ?>" rel="icon"/>
    <?php endif; ?>
    <?php if($this->document->getLinks()): ?>
    <?php foreach ($this->document->getLinks() as $link) { ?>
    <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>"/>
    <?php } ?>
    <?php endif;?>
    <meta name="revisit-after" content="1 Day">
    <?php if($this->document->getSiteName()): ?>
    <meta name="author" content="<?php echo $this->document->getSiteName(); ?>">
    <meta property="og:site_name" content="<?php echo $this->document->getSiteName(); ?>">
    <?php endif; ?>
    <?php if($this->document->getTitle()): ?>
    <meta property="og:title" content="<?php echo $this->document->getTitle(); ?>">
    <?php endif; ?>
    <meta property="og:image" content="https://www.rohlik.cz/images/social/share-huge.jpg">
    <?php if ($this->document->getDescription()): ?>
    <meta property="og:description" content="<?php echo $this->document->getDescription(); ?>">
    <?php endif; ?>
    <meta property="og:type" content="website">
    <?php if($this->document->getSiteBase()):?>
    <meta property="og:url" content="<?php echo $this->document->getSiteBase(); ?>">
    <?php endif; ?>
    <meta property="fb:app_id" content="615828701867578">
    <meta property="fb:admins" content="666678592">
    <!--[if lte IE 8]>
    <script src="<?php echo $this->document->getSiteBase(); ?>js/html5shiv.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="static/js/main.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,600,700,800&subset=latin-ext'
          rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/static/css/style.css">
    <?php if($this->document->getStyles()): ?>
    <?php foreach ($this->document->getStyles() as $style): ?>
    <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
    <?php endforeach; ?>
    <?php endif;?>
    <?php if($this->document->getLinks()): ?>
    <?php foreach ($this->document->getLinks() as $link) { ?>
    <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
    <?php } ?>
    <?php endif;?>
    <?php if($this->document->getScripts()): ?>
    <?php foreach($this->document->getScripts() as $script): ?>
    <script src="<?php echo $script; ?>" type="text/javascript"></script>
    <?php endforeach;?>
    <?php endif;?>
</head>
<body>
<!-- Header -->
<?php echo isset($header) ? $header : ''; ?>
<!-- End Header -->
<!-- Nav -->
<?php echo isset($navigation) ? $navigation : ''; ?>
<!-- End Nav -->
<!-- Breadcrumb -->
<?php echo (isset($breadcrumbs) && is_string($breadcrumbs)) ? $breadcrumbs : ''; ?>
<!-- End Breadcrumb -->
<!-- Main Content -->
<?php echo isset($main_content) ? $main_content : ''; ?>
<!-- End Main Content -->
<!-- Footer -->
<?php echo isset($footer) ? $footer : ''; ?><!-- End Footer -->
<div class="tooltip"></div>
<div id="fb-root"></div>
</body>
</html>