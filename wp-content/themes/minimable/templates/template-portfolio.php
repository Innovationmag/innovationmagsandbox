<?php
/**
 * Template Name: Template Portfolio
 * 
 */?>
	<div class="container" id="portfolio">
		<h2 class="section-title"><?php the_title(); ?></h2>
		<?php query_posts('post_type=portfolio'); ?>
		<?php if (have_posts()) : while(have_posts()) : the_post() ?>		
		<div class="row item">
			<div class="span8">
				<?php the_post_thumbnail('portfolio'); ?>
			</div>
			<div class="span4 border description-text">
				<h3><?php the_title(); ?></h3>
				<?php the_content(); ?>
				<?php if (get_post_meta($post->ID, 'fw_link_portfolio', true)) { ?>
				<a class="link-portfolio" href="http://<?php echo get_post_meta($post->ID, 'fw_link_portfolio', true);?>"><?php echo $fw_label_portfolio_link ?></a>
				<?php } ?>
			</div>
		</div>
		<?php endwhile; endif; ?>
	</div>