<?php
	if( is_admin()){
    wp_enqueue_script( 'farbtastic' );
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'my-theme-options', get_template_directory_uri() . '/admin/js/theme-options.js', array( 'farbtastic', 'jquery' ) );
	}

$themename = "Minimable";
$shortname = "fw";
$options = array (
    array( "name" => $themename." Options",
           "type" => "title"),
    array( "type" => "open"),
    
    /* General Settings */
    array( "name" => "Documentation",
    	   "id" => "sec-one",
           "type" => "section"),
           
    array( "name" => "",
           "desc" => "",
           "id" => "",
           "type" => "docs",
           "std" => ""),
           
		array( "type" => "end-section"),
	       
    array( "name" => "General Settings",
    	   "id" => "sec-two",
           "type" => "section"),
           
   	array( "name" => "Logo",
           "desc" => "Upload your logo image or copy and paste its image url if you have just uploaded it. <br/>The height must be 45px.",
           "id" => $shortname."_logo",
           "type" => "image",
           "std" => ''.get_bloginfo('template_directory').'/img/logo.png'),
           
    array( "name" => "Logo Menu Bar",
           "desc" => "Upload your mini logo image that appears on sticky menu. The height must be 14px",
           "id" => $shortname."_logo_mini",
           "type" => "image",
           "std" => ''.get_bloginfo('template_directory').'/img/logo-mini.png'),
           
    array( "name" => "Favicon",
           "desc" => "Upload the image. Then copy and paste the image url in the field on top.",
           "id" => $shortname."_favicon",
           "type" => "image",
           "std" => ''.get_bloginfo('template_directory').'/img/favicon.ico'),
           
    array( "name" => "Main Color",
           "desc" => "Set the main color of your site",
           "id" => $shortname."_main_color",
           "type" => "color",
           "std" => '#01a3b2'),
           
    array( "name" => "Number of Pages",
           "desc" => "How many pages do you use in your site?",
           "id" => $shortname."_page_number",
           "type" => "text",
           "std" => '5'),
   	array( "name" => "Label for Portfolio Link",
           "desc" => "Insert the label for the Portfolio link",
           "id" => $shortname."_label_portfolio_link",
           "type" => "text",
           "std" => 'Go to work'),
           
    array( "name" => "Enable/Disable Scrollorama",
           "desc" => "If you don't like Scrollorama effect, you can disable it.",
           "id" => $shortname."_onoff_scrollorama",
           "type" => "checkbox",
           "std" => 'checked'),
     
    array( "name" => "Enable/Disable Animation Big Title",
           "desc" => "If you don't like animation text effect, you can disable it.",
           "id" => $shortname."_onoff_animation_title",
           "type" => "checkbox",
           "std" => 'checked'),     
	
		array( "name" => "Enable/Disable Custom Css",
           "desc" => "If you want to add some extra style, enable custom css and edit custom.css file.",
           "id" => $shortname."_onoff_custom_css",
           "type" => "checkbox",
           "std" => 'checked'),
    
    array( "name" => "Enable/Disable Custom Javascript",
           "desc" => "If you want to add some extra javascript function, enable custom javascriot and edit custom.js file, in js directory.",
           "id" => $shortname."_onoff_custom_js",
           "type" => "checkbox",
           "std" => 'checked'),      
    
    array( "type" => "submit"),       
    
    array( "type" => "end-section"),
    
               
    /* Social Settings */       
    array( "name" => "Footer Settings",
           "id" => "sec-three",
           "type" => "section"),
    
    array( "name" => "Footer text",
           "desc" => "Enter text used in the right side of the footer. It can be HTML.",
           "id" => $shortname."_footer_text",
           "type" => "text",
           "std" => ""),
           
    /* Analytics Code */       
		array( "name" => "Google Analytics Code",
           "desc" => "Paste your Google Analytics or other tracking code in this box.",
           "id" => $shortname."_ga_code",
           "type" => "textarea",
           "std" => ""),
    array( "type" => "submit"),       
    array( "type" => "end-section"),
    
    /* Mailchimp Settings */
    array( "name" => "Premium Version Is Coming Very Soon",
    	   "id" => "sec-four",
           "type" => "section"),
    array( "name" => "",
           "desc" => "",
           "id" => "",
           "type" => "mailchimp",
           "std" => ""),
    array( "type" => "end-section"),
    array( "type" => "close"));

    
function mytheme_add_admin() {
 
	global $themename, $shortname, $options;
	 
	if ( $_GET['page'] == basename(__FILE__) ) {
		if ( 'save' == $_REQUEST['action'] ) {
		 
		foreach ($options as $value) {
		update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }
		 
		foreach ($options as $value) {
		if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }
		 
		header("Location: themes.php?page=theme-options.php&saved=true");
		die;
		 
		} else if( 'reset' == $_REQUEST['action'] ) {
		 
		foreach ($options as $value) {
		delete_option( $value['id'] ); }
		 
		header("Location: themes.php?page=theme-options.php&reset=true");
		die;
		 
		}
	}
 	add_menu_page($themename." Options", "".$themename." Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');
}
function mytheme_add_init() {  
	$file_dir=get_bloginfo('template_directory');  
	wp_enqueue_style("functions", $file_dir."/admin/css/theme-option.css", false, "1.0", "all");  
} 

function mytheme_admin() {
 
	global $themename, $shortname, $options;
	 
	if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
	if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
	 
?>

	<div class="wrap">
		<h2><?php echo $themename; ?> Settings</h2>
	 	<form method="post">
		<?php foreach ($options as $value) {
			switch ( $value['type'] ) {
				case "open":
		?>
			<div style="width:800px">
		<?php break;
		 case "close":
		?>
		 	</div>
		 
		<?php break;
		
		case "section" :
		?>
			<div class="accordion <?php echo $value['id']; ?>">
				<h3><?php echo $value['name']; ?></h3>
				<div class="options-<?php echo $value['id']; ?> options">
		<?php break;
	
		case "end-section" :
		?>
				</div>
			</div>
		<?php break;
		
		case "subsection" :
		?>
			<h4><?php echo $value['name']; ?></h4>
		<?php break;
		
		case 'text':
		?>
			<div class="text <?php echo $value['id']; ?>">
				<h4><?php echo $value['name']; ?></h4>
				<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( stripslashes(esc_attr(get_settings($value['id']))) != "") { echo stripslashes(esc_attr(get_settings((esc_attr($value['id']) )))); } else { echo $value['std']; } ?>" /><br/>
				<small><?php echo $value['desc']; ?></small>
			</div>
		<?php
		break;
		
		case 'color':
		?>
			<h4><?php echo $value['name']; ?></h4>
			<div class="changecolor">
				<input type="text" name="<?php echo $value['id']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" />
				<input type='button' class='pickcolor button-secondary' value='Select Color' />
				<div class='colorpicker'></div>
			</div>
			<small><?php echo $value['desc']; ?></small>
			<?php 
		break;
		
		case 'image' :
		?>
			<div class="fw_upload">
				<h4><?php echo $value['name']; ?></h4>
				<input style="width:400px;" class="upload_image" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" />
				<input class="upload_image_button" type="button" value="Upload Image" />
				<br /><small><?php echo $value['desc']; ?></small>
			</div>
		<?php
		break;
		
		case 'textarea':
		?>
			<h4><?php echo $value['name']; ?></h4>
			<div class="areatesto">
				<textarea name="<?php echo $value['id']; ?>" style="width:400px; height:200px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( stripslashes(get_settings( $value['id'] )) != "") { echo stripslashes(get_settings( $value['id'] )); } else { echo $value['std']; } ?></textarea><br/>
				<small><?php echo $value['desc']; ?></small>
			</div>
		<?php
		break;
		case 'checkbox'
		?>
			<h4><?php echo $value['name']; ?></h4>
			<div class="check">  
				<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>  
			    <?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>  
				<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />  
			    <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>  
			</div>  
		<?php break;
		
		case 'mailchimp' :
		?>
			<div id="premium-box">
				<a href="#" id="close">Close</a>
				<p class="box-title">Mini<span>mable</span> Premium is coming</p>
				<ul>
					<li><span>Full customization (font, colors, everything)</span></li>
					<li><span>More page templates</span></li>
					<li><span>Shortcodes</span></li>
					<li><span>Blog section</span></li>
					<li><span>Ajaxable and filterable Portfolio</span></li>
					<li><span>And more</span></li>
				</ul>
				<p id="when-ready">Would you know <strong>when</strong> the premium version <strong>is ready?</strong> Fill the form below!<br/>
				   Only <strong>subscribers</strong> are having a <strong>big discount!</strong>
				</p>
				<!-- Begin MailChimp Signup Form -->
				<div id="mc_embed_signup">
					<form action="http://fedeweb.us7.list-manage1.com/subscribe/post?u=d1b2ae4a2522ecb1883b26b71&amp;id=9a08c6daee" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
						<fieldset>
							<input type="text" name="FNAME" class="" id="mce-FNAME" value="Your Name" onfocus="if (this.value=='Your Name') this.value='';" onblur="if (this.value=='') this.value='Your Name';">
							<input type="email" name="EMAIL" class="required email last" id="mce-EMAIL" value="Your Email" onfocus="if (this.value=='Your Email') this.value='';" onblur="if (this.value=='') this.value='Your Email';">
						</fieldset>
						<div id="mce-responses" class="clear">
							<div class="response" id="mce-error-response" style="display:none"></div>
							<div class="response" id="mce-success-response" style="display:none"></div>
						</div>	
						<div class="clear"><input type="submit" value="I want the discount" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
					</form>
				</div>
			</div>
		<?php 
		break;
		case 'docs' :
		?>
			<p>Read documentation before using theme, on this <a href="http://minimable.fedeweb.net/docs/index.html" target="_blank">link</a>.</p>
		<?php 
		break;
		case 'submit':
		?>
			<p class="submit">
				<input name="save" type="submit" value="Save changes" />
				<input type="hidden" name="action" value="save" />
			</p>
		</form>
		<?php
		break;
		}
	}
?>
	</form>
	<form method="post">
		<p class="submit">
			<input name="reset" type="submit" value="Reset" />
			<input type="hidden" name="action" value="reset" />
		</p>
	</form>
</div>

<?php
}

add_action('admin_menu', 'mytheme_add_admin');
add_action('admin_init', 'mytheme_add_init'); 
?>