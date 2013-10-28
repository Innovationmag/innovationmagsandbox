<?php
/**
 * Template Name: Template Staff
 * 
 */?>
 	<div class="container">
		<div class="row">
			<div class="span12">
				<h2 class="section-title"><?php the_title(); ?></h2>
				<div class="row">
					<div class="span3 border" id="staff-desc" style="position:relative;">
						<h3 class="col-title color"><?php echo get_post_meta($post->ID, 'fw_title_left', true);?></h3>
						<?php the_content(); ?>
					</div>
					<div class="span9 border" id="staff-team" style="position:relative;">				
						<ul class="nav nav-tabs">
						<?php query_posts('post_type=team'); ?>
						<?php if (have_posts()) : while(have_posts()) : the_post() ?>
						  <li><a href="#<?php echo get_the_ID(); ?>" data-toggle="tab"><?php the_title(); ?></a></li>
						  <?php endwhile; endif; ?>
						</ul>
						<div class="tab-content row">
						<?php if (have_posts()) : while(have_posts()) : the_post() ?>
							<div class="tab-pane fade" id="<?php echo get_the_ID(); ?>">
							  	<div class="span3">
							  		<?php the_post_thumbnail('staff', array('class' => 'img-circle')); ?>
							  	</div>
							  	<div class="span6 description-text">
							  		<h3><?php the_title(); ?></h3>
							  		<?php the_content(); ?>
								</div>
						  	</div>
						  <?php endwhile; endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>