<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>



	<div id="content" class="narrowcolumn">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="page" id="post-<?php the_ID(); ?>">
        <div class="postHeader_left"></div>
		<div class="postHeader">
		<h3><?php the_title(); ?></h3>
        </div>
			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div>
		</div>
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
