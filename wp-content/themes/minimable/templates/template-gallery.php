<?php
/**
 * Template Name: Template Gallery
 * 
 */?>
<?php
	$args = array('numberposts' => -1, // Per prendere tutti i post
	'orderby' => 'menu_order', // Utilizzo l'ordine scelto nell'editor
	'order' => 'ASC', //ordine di visualizzzazione delle immagini
	'post_mime_type' => 'image', // Prendo solo immagini, escludendo altri tipi di file
	'post_parent' => $post -> ID,
	'post_status' => null, 'post_type' => 'attachment');
	$attachments = get_children($args);
	$thumb_attr = array (
		'class'	=> 'img-circle'
		);
	?>
	<div class="container">
		<div class="row">
			<div class="span12">
				<h2 class="section-title"><?php the_title(); ?></h2>
				<div class="row">	
				    <ul class="unstyled thumbnails row gallery" >
				    	<?php if ($attachments) {
				    		foreach($attachments as $attachment) {
				    			$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);	    			
				    			$image_small =  wp_get_attachment_image( $attachment->ID, 'small', 0 , $thumb_attr );
				    			$image_big =  wp_get_attachment_image_src( $attachment->ID, 'full' ); ?>
		    			<li class="span3">
							<a href="<?php echo $image_big[0] ?>" class="swipebox magnifier" title ="<?php echo $alt; ?>" style="display:block;text-align:center;">
								<?php echo $image_small ?>
							</a>
						</li>
						<?php }
						} ?>
					</ul>
				</div>
			</div>
		</div>
	</div>