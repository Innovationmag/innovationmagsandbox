<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />


<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>
</head>
<body>
<div id="page-wrapper">
<div id="page">
	<div id="header">
        <div id="menu">
            <?php wp_page_menu('show_home=1&sort_column=menu_order'); ?>
        </div>
        
        <!--<h2 id="slogan"><?php echo bloginfo('description'); ?></h2>-->
        <?php get_search_form(); ?>    
    </div>
    <div class="site_title">
    	<h1><a href="<?php echo get_option('home'); ?>/"><?php echo bloginfo('name'); ?></a></h1>
    </div>    
    <div id="content-wrapper">
