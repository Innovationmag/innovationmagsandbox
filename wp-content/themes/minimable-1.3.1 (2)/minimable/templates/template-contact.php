<?php
/**
 * Template Name: Template Contact
 * 
 */?>
 	<div class="container">
 		<h2 class="section-title"><?php the_title(); ?></h2>
 		<div class="row">
			<div class="span4" id="find-us">
				<h3>
					<?php if (get_post_meta($post->ID, 'fw_contact_info_title', true)) {
					echo get_post_meta($post->ID, 'fw_contact_info_title', true);
					} else { ?> 
					Find Us
					<?php }?>
				</h3>
				<ul class="unstyled">
					<?php if (get_post_meta($post->ID, 'fw_address_label', true)) { ?>
					<li><span class="icon-map-marker contact-icon"></span> <span class="color"><?php echo get_post_meta($post->ID, 'fw_address_label', true);?>:</span> <?php echo get_post_meta($post->ID, 'fw_address_field', true);?></li>
					<?php } ?>
					<?php if (get_post_meta($post->ID, 'fw_phone_label', true)) { ?>
					<li><span class="icon-signal contact-icon"></span> <span class="color"><?php echo get_post_meta($post->ID, 'fw_phone_label', true);?>:</span> <?php echo get_post_meta($post->ID, 'fw_phone_field', true);?></li>
					<?php } ?>
					<?php if (get_post_meta($post->ID, 'fw_fax_label', true)) { ?>
					<li><span class="icon-print contact-icon"></span> <span class="color"><?php echo get_post_meta($post->ID, 'fw_fax_label', true);?>:</span> <?php echo get_post_meta($post->ID, 'fw_fax_field', true);?></li>
					<?php } ?>
					<?php if (get_post_meta($post->ID, 'fw_email_label', true)) { ?>
					<li><span class="icon-envelope contact-icon"></span> <span class="color"><?php echo get_post_meta($post->ID, 'fw_email_label', true);?>:</span> <a class="email-link" href="mailto:<?php echo get_post_meta($post->ID, 'fw_email_field', true);?>"><?php echo get_post_meta($post->ID, 'fw_email_field', true);?></a></li>  
					<?php } ?>
				</ul>
				<ul class="unstyled social">
					<?php if (get_post_meta($post->ID, 'fw_fb_link', true)) { ?>
						<li>
							<a href="<?php echo (get_post_meta($post->ID, 'fw_fb_link', true))?>">
								<span class="sprite-social fb-icon"></span>
							</a>
						</li>
					<?php } ?>
					<?php if (get_post_meta($post->ID, 'fw_tw_link', true)) { ?>
						<li>
							<a href="<?php echo (get_post_meta($post->ID, 'fw_tw_link', true))?>">
								<span class="sprite-social tw-icon"></span>
							</a>
						</li>
					<?php } ?>
					<?php if (get_post_meta($post->ID, 'fw_gp_link', true)) { ?>
						<li>
							<a href="<?php echo (get_post_meta($post->ID, 'fw_gp_link', true))?>">
								<span class="sprite-social gp-icon"></span>
							</a>
						</li>
					<?php } ?>
					<?php if (get_post_meta($post->ID, 'fw_pt_link', true)) { ?>
						<li>
							<a href="<?php echo (get_post_meta($post->ID, 'fw_pt_link', true))?>">
								<span class="sprite-social pt-icon"></span>
							</a>
						</li>
					<?php } ?>
					<?php if (get_post_meta($post->ID, 'fw_ln_link', true)) { ?>
						<li>
							<a href="<?php echo (get_post_meta($post->ID, 'fw_ln_link', true))?>">
								<span class="sprite-social ln-icon"></span>
							</a>
						</li>
					<?php } ?>
					<?php if (get_post_meta($post->ID, 'fw_yt_link', true)) { ?>
						<li>
							<a href="<?php echo (get_post_meta($post->ID, 'fw_yt_link', true))?>">
								<span class="sprite-social yt-icon"></span>
							</a>
						</li>
					<?php } ?>
				</ul>
			</div>    
			<div class="span8" id="contact-info">
				<h3>
					<?php if (get_post_meta($post->ID, 'fw_contact_form_title', true)) {
					echo get_post_meta($post->ID, 'fw_contact_form_title', true);
					} else { ?> 
					Contact Us
					<?php }?>
				</h3>
				<?php echo apply_filters('the_content', get_post_meta($post->ID, 'fw_contact_form', true));?>
			</div>	
		</div>
	</div>