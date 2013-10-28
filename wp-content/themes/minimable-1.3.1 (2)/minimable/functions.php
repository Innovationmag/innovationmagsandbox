<?php
	/**
	 * Starkers functions and definitions
	 *
	 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
	 *
 	 * @package 	WordPress
 	 * @subpackage 	Starkers
 	 * @since 		Starkers 4.0
	 */

	/* ========================================================================================================================
	
	Required external files
	
	======================================================================================================================== */
	require_once ('admin/theme-options.php');
	require_once( 'external/starkers-utilities.php' );
	require_once( 'admin/metaboxes.php' );
	include("admin/update_notifier.php");
			

	/* ========================================================================================================================
	
	Theme specific settings

	Uncomment register_nav_menus to enable a single menu with the title of "Primary Navigation" in your theme
	
	======================================================================================================================== */

	add_theme_support('post-thumbnails');
	add_image_size('small',270,270,true);
	add_image_size('big',9999,400);
	add_image_size('staff',270,270,true);
	add_image_size('portfolio',770,440,true);
	
	register_nav_menus(array('primary' => 'Primary Navigation'));

	/* ========================================================================================================================
	
	Actions and Filters
	
	======================================================================================================================== */

	add_action( 'wp_enqueue_scripts', 'minimable_script_enqueuer' );

	/* ========================================================================================================================
	
	Scripts and styles
	
	======================================================================================================================== */


	function minimable_script_enqueuer() {
		
		if( !is_admin()){
			wp_deregister_script('jquery');
			wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"), false, null, true);
			wp_enqueue_script('jquery');
		}
		wp_register_script( 'html5', get_template_directory_uri().'/js/html5.js', array( 'jquery' ),null);
		wp_enqueue_script( 'html5' );
		
		wp_register_script( 'bootstrap-js', get_template_directory_uri().'/js/bootstrap.min.js', array( 'jquery' ),null,true );
		wp_enqueue_script( 'bootstrap-js' );
		
		wp_register_script( 'easing', get_template_directory_uri().'/js/easing.js', array( 'jquery' ),null,true );
		wp_enqueue_script( 'easing' );
			
		wp_register_script( 'ios-fix', get_template_directory_uri().'/js/ios-orientationchange-fix.js', array( 'jquery' ),null,true );
		wp_enqueue_script( 'ios-fix' );	
		
		wp_register_script( 'swipe-box', get_template_directory_uri().'/js/jquery.swipebox.min.js', array( 'jquery' ),null,true );
		wp_enqueue_script( 'swipe-box' );
					
		wp_register_script( 'site', get_template_directory_uri().'/js/site.js', array( 'jquery' ),null,true );
		wp_enqueue_script( 'site' );
		
		if (get_option('fw_onoff_scrollorama')) {
			wp_register_script( 'tween-max', get_template_directory_uri().'/js/TweenMax.min.js', array( 'jquery' ),null,true );
			wp_enqueue_script( 'tween-max' );
			wp_register_script( 'scrollorama', get_template_directory_uri().'/js/jquery.superscrollorama.js', array( 'jquery' ),null,true );
			wp_enqueue_script( 'scrollorama' );	
			wp_register_script( 'scrolling-effect', get_template_directory_uri().'/js/scrolling-effect.js', array( 'jquery' ),null,true );
			wp_enqueue_script( 'scrolling-effect' );
		}
		if (get_option('fw_onoff_animation_title')) {
			wp_register_script( 'title-animation', get_template_directory_uri().'/js/title-animation.js', array( 'jquery' ),null,true );
			wp_enqueue_script( 'title-animation' );
		}
		if (get_option('fw_onoff_custom_js')) {
			wp_register_script( 'custom-js', get_template_directory_uri().'/js/custom.js', array( 'jquery' ),null,true );
			wp_enqueue_script( 'custom-js' );
        }
		wp_register_style( 'bootstrap', get_stylesheet_directory_uri().'/css/bootstrap.min.css', '', '', 'screen' );
        wp_enqueue_style( 'bootstrap' );
		
		wp_register_style( 'bootstrap-responsive', get_stylesheet_directory_uri().'/css/bootstrap-responsive.min.css', '', null, 'screen' );
        wp_enqueue_style( 'bootstrap-responsive' );
		
		wp_register_style( 'open-sans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,300', '', '', 'screen' );
        wp_enqueue_style( 'open-sans' );
		wp_register_style( 'screen', get_stylesheet_directory_uri().'/style.css', '', null, 'screen' );
        wp_enqueue_style( 'screen' );
		
		if (get_option('fw_onoff_custom_css')) {
			wp_register_style( 'custom-style', get_stylesheet_directory_uri().'/custom.css', '', null, 'screen' );
	        wp_enqueue_style( 'custom-style' );
        }
		wp_register_style( 'swipebox', get_stylesheet_directory_uri().'/css/swipebox.css', '', null, 'screen' );
        wp_enqueue_style( 'swipebox' );
	}
	

	/* ========================================================================================================================
	
	Custom Post Type
	
	======================================================================================================================== */
	
	add_action( 'init', 'create_post_type' );
	
	function create_post_type() {
		register_post_type( 'team',
			array(
				'labels' => array(
				'name' => __( 'Team' ),
				'singular_name' => __( 'Team Member' ),
				'add_new' => __( 'Add New' ),
				'add_new_item' => __( 'Add New Member' ),
				'edit' => __( 'Edit' ),
				'edit_item' => __( 'Edit Member' ),
				'new_item' => __( 'New Member' ),		
			),
			'public' => true,
			'supports' => array( 'title', 'editor','thumbnail' )
			)
			
		);
		register_post_type( 'portfolio',
			array(
				'labels' => array(
				'name' => __( 'Portfolio' ),
				'singular_name' => __( 'Work ' ),
				'add_new' => __( 'Add New' ),
				'add_new_item' => __( 'Add New Work' ),
				'edit' => __( 'Edit' ),
				'edit_item' => __( 'Edit Work' ),
				'new_item' => __( 'New Work' ),		
			),
			'public' => true,
			'supports' => array( 'title', 'editor','thumbnail' )
			)	
		);
	}
	/* ========================================================================================================================
	
	Shortcode
	
	======================================================================================================================== */
	
	function fw_color($atts,$content = null) {
		return '<span>'.$content.'</span>';
	}
	add_shortcode("color", "fw_color");
	add_filter( 'the_title', 'do_shortcode' );
	
	
