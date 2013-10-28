<?php
/**
 * Template Name: Template Home 
 * 
 */?>
	<div class="container">
		<div class="row" id="home-header">
			<div id="logo" class="span4">
				<img src="<?php echo $fw_logo; ?>" alt="<?php bloginfo('name') ?>" height="45" />
			</div>
			<h2 class="span8 right" id="mini-slogan"><?php bloginfo( 'description' ); ?></h2>
		</div>
		<?php 
			if (get_post_meta($post->ID, 'fw_big_one', true) && 
				 get_post_meta($post->ID, 'fw_big_two', true) && 
				 get_post_meta($post->ID, 'fw_big_three', true))
			{ 
		?>
		<div class="row three-big" id="slogan">
	   		<h1 id="first-title" class="big-title"><?php $first_title = get_post_meta($post->ID, 'fw_big_one', true); echo do_shortcode( $first_title );?></h1>
	   		<h1 id="second-title" class="big-title"><?php $second_title = get_post_meta($post->ID, 'fw_big_two', true); echo do_shortcode( $second_title );?></h1>
	   		<h1 id="third-title" class="big-title"><?php $third_title =  get_post_meta($post->ID, 'fw_big_three', true); echo do_shortcode( $third_title );?></h1>
	   	</div>
	   	<?php } 
	   	else if (get_post_meta($post->ID, 'fw_big_one', true) && 
				 get_post_meta($post->ID, 'fw_big_two', true)) 
			 { 
	 	?>
	   	<div class="row two-big" id="slogan">
	   		<h1 id="first-title" class="big-title"><?php $first_title = get_post_meta($post->ID, 'fw_big_one', true); echo do_shortcode( $first_title );?></h1>
	   		<h1 id="second-title" class="big-title"><?php $second_title = get_post_meta($post->ID, 'fw_big_two', true); echo do_shortcode( $second_title );?></h1>
	   	</div>
	   	<?php  } else if (get_post_meta($post->ID, 'fw_big_one', true)) { ?>
	   	<div class="row one-big" id="slogan">
	   		<h1 id="first-title" class="big-title"><?php $first_title = get_post_meta($post->ID, 'fw_big_one', true); echo do_shortcode( $first_title );?></h1>
   		</div>
	   	<?php } ?>	
	   	<div class="row">
	   		<nav class="<?php if (get_post_meta($post->ID, 'fw_label_four', true)) { ?> offset2 <?php } else {?>offset3 <?php } ?> span8" id="nav-home">
		   		<ul class="row">
		   			<li class="span2">
		   				<a class="circle-menu circle-black" href="#page-2"><span class="label-link"><?php echo get_post_meta($post->ID, 'fw_label_one', true);?></span></a>
		   				<span class="arrow"></span>
		   			</li>
		   			<li class="span2">
		   				<a class="circle-menu" href="#page-3"><span class="label-link"><?php echo get_post_meta($post->ID, 'fw_label_two', true);?></span></a>
		   				<span class="arrow"></span>
		   			</li>
		   			<li class="span2">
		   				<a class="circle-menu circle-black" href="#page-4"><span class="label-link"><?php echo get_post_meta($post->ID, 'fw_label_three', true);?></span></a>
		   				<span class="arrow"></span>
		   			</li>
		   			<?php if (get_post_meta($post->ID, 'fw_label_four', true)) { ?>
		   			<li class="span2">
		   				<a class="circle-menu" href="#page-5"><span class="label-link"><?php echo get_post_meta($post->ID, 'fw_label_four', true);?></span></a>
		   				<span class="arrow"></span>
		   			</li>
		   			<?php } ?>
		   		</ul>
	   		</nav>
	   	</div>
	</div>