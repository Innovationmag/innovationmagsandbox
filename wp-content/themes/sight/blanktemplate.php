<?php
/**
	Template Name: Page with background only

*/
?>
<html>
<head>
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<style type="text/css">

#page-content { width: 800px; margin: 20px auto; }
</style>
<title><?php wp_title( '|', true, 'right' ); bloginfo('url'); ?></title>
<?php wp_head(); ?>
</head>

<body>
<?php while (have_posts()) : the_post(); ?>
<div id="page-content">
	<?php the_content(); endwhile; ?>
</div>
</body>
</html>