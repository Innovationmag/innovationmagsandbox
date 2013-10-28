<?php
/**
 * @package WordPress
 * @since 1.0
 */
?>
<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>
<?php global $options;
	foreach ($options as $value) {
		if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
	}
?>
<?php for($i=1;$i<=$fw_page_number ;$i++) { ?>
<section id="page-<?php echo $i ?>">
<?php 
	$pagePosts = new WP_Query();
	$pagePosts->query('pagename=page-'.$i.'');
	if($pagePosts->have_posts()): while($pagePosts->have_posts()): $pagePosts->the_post();	
		$template_file = get_post_meta( get_the_ID(), '_wp_page_template', TRUE );
		if ($template_file=='templates/template-home.php') { 
		   include 'templates/template-home.php'; 
		}
		elseif ($template_file=='templates/template-staff.php') { 
		   include 'templates/template-staff.php'; 
		}
		elseif ($template_file=='templates/template-gallery.php') { 
		   include 'templates/template-gallery.php'; 
		}
		elseif ($template_file=='templates/template-portfolio.php') { 
		   include 'templates/template-portfolio.php'; 
		}
		elseif ($template_file=='templates/template-contact.php') { 
		   include 'templates/template-contact.php'; 
		}		 
	endwhile;
	else: ?>
	<p class="activation-message">You have this blank page because you activate this theme for the first time. Before using Minimable, read the <a href="http://minimable.fedeweb.net/docs/index.html" target="_blank ">documentation</a> ;)</p>
	<?php endif; ?>
</section>
<?php  } ?>	  
<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer') ); ?>