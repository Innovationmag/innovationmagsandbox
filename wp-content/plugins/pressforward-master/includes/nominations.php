<?php

/**
 * Functionality related to nominations
 */
class PF_Nominations {
	function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action('edit_post', array( $this, 'send_nomination_for_publishing'));
		add_filter( 'manage_edit-nomination_columns', array ($this, 'edit_nominations_columns') );
		add_action( 'manage_nomination_posts_custom_column',  array ($this, 'nomination_custom_columns') );
		add_filter( "manage_edit-nomination_sortable_columns", array ($this, "nomination_sortable_columns") );

	}

	/**
	 * Register the 'nomination' post type
	 *
	 * @since 1.7
	 */
	function register_post_type() {
		$args = array(
			'labels' => array(
				'name' => __( 'Nominations', 'pf' ),
				'singular_name' => __( 'Nomination', 'pf' ),
				'add_new' => __('Nominate', 'pf'),
				'add_new_item' => __('Add New Nomination', 'pf'),
				'edit_item' => __('Edit Nomination', 'pf'),
				'new_item' => __('New Nomination', 'pf'),
				'view_item' => __('View Nomination', 'pf'),
				'search_items' => __('Search Nominations', 'pf'),
				'not_found' => __('No nominations found', 'pf'),
				'not_found_in_trash' => __('No nominations found in Trash', 'pf')
			),
			'description' => __('Posts from around the internet nominated for consideration to public posting', 'pf'),
			//Not available to non-users.
			'public' => false,
			//I want a UI for users to use, so true.
			'show_ui' => true,
			//But not the default UI, we want to attach it to the plugin menu.
			'show_in_menu' => false,
			//Linking in the metabox building function.
			'register_meta_box_cb' => array($this, 'nominations_meta_boxes'),
			'capability_type' => 'post',
			//The type of input (besides the metaboxes) that it supports.
			'supports' => array('title', 'editor', 'thumbnail', 'revisions'),
			//I think this is set to false by the public argument, but better safe.
			'has_archive' => false
		);

		register_post_type('nomination', $args);

	}

	/**
	 * Callback for registering meta boxes on 'nomination' post type
	 *
	 * @since 1.7
	 */
	public function nominations_meta_boxes() {
		add_meta_box(
			'pf-nominations',
			__('Nomination Data', 'pf'),
			array($this, 'nominations_box_builder'),
			'nomination',
			'side',
			'high'
		);
	}

	# The builder for the box that shows us the nomination metadata.
	public function nominations_box_builder() {
		global $post;
		//wp_nonce_field( 'nominate_meta', 'nominate_meta_nonce' );
		$origin_item_ID = get_post_meta($post->ID, 'origin_item_ID', true);
		$nomination_count = get_post_meta($post->ID, 'nomination_count', true);
		$submitted_by = get_post_meta($post->ID, 'submitted_by', true);
		$source_title = get_post_meta($post->ID, 'source_title', true);
		$posted_date = get_post_meta($post->ID, 'posted_date', true);
		$nom_authors = get_post_meta($post->ID, 'authors', true);
		$nomination_permalink = get_post_meta($post->ID, 'nomination_permalink', true);
		$date_nominated = get_post_meta($post->ID, 'date_nominated', true);
		$user = get_user_by('id', $submitted_by);
		$item_tags = get_post_meta($post->ID, 'item_tags', true);
		$source_repeat = get_post_meta($post->ID, 'source_repeat', true);
		if (!empty($origin_item_ID)){
			$this->meta_box_printer(__('Item ID', 'pf'), $origin_item_ID);
		}
		if (empty($nomination_count)){$nomination_count = 1;}
		$this->meta_box_printer(__('Nomination Count', 'pf'), $nomination_count);
		if (empty($user)){ $user = wp_get_current_user(); }
		$this->meta_box_printer(__('Submitted By', 'pf'), $user->display_name);
		if (!empty($source_title)){ 
			$this->meta_box_printer(__('Feed Title', 'pf'), $source_title);
		}
		if (empty($posted_date)){
			$this->meta_box_printer(__('Posted by source on', 'pf'), $posted_date);
		} else {
			$this->meta_box_printer(__('Source Posted', 'pf'), $posted_date);
		}
		$this->meta_box_printer(__('Source Authors', 'pf'), $nom_authors);
		$this->meta_box_printer(__('Source Link', 'pf'), $nomination_permalink, true, __('Original Post', 'pf'));
		$this->meta_box_printer(__('Item Tags', 'pf'), $item_tags);
		if (empty($date_nominated)){ $date_nominated = date(DATE_ATOM); }
		$this->meta_box_printer(__('Date Nominated', 'pf'), $date_nominated);
		if (!empty($source_repeat)){ 
			$this->meta_box_printer(__('Repeated in Feed', 'pf'), $source_repeat);
		}

	}

	public function send_nomination_for_publishing() {
		global $post;
		// verify if this is an auto save routine.
		// If it is our form has not been submitted, so we dont want to do anything
		//if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		if ( isset( $_POST['post_status'] ) && isset( $_POST['post_type'] ) && ($_POST['post_status'] == 'publish') && ($_POST['post_type'] == 'nomination')){
		//print_r($_POST); die();
			$item_title = $_POST['post_title'];
			$item_content = $_POST['post_content'];
			$data = array(
				'post_status' => 'draft',
				'post_type' => 'post',
				'post_title' => $item_title,
				'post_content' => $item_content,
			);
			//Will need to use a meta field to pass the content's md5 id around to check if it has already been posted.

			//We assume that it is already in nominations, so no need to check there. This might be why we can't use post_exists here.
			//No need to origonate the check at the time of the feed item either. It can't become a post with the proper meta if it wasn't a nomination first.
			$item_id = get_post_meta($_POST['ID'], 'origin_item_ID', true);
			$nom_date = $_POST['aa'] . '-' . $_POST['mm'] . '-' . $_POST['jj'];

			//Now function will not update nomination count when it pushes nomination to publication.
			$post_check = $this->get_post_nomination_status($nom_date, $item_id, 'post', false);

			//Alternative check with post_exists? or use same as above?
			if ($post_check != true) {
				$newPostID = wp_insert_post( $data );
				add_post_meta($newPostID, 'origin_item_ID', $item_id, true);
				$nomCount = get_post_meta($_POST['ID'], 'nomination_count', true);
				add_post_meta($newPostID, 'nomination_count', $nomCount, true);
				$userID = get_post_meta($_POST['ID'], 'submitted_by', true);
				add_post_meta($newPostID, 'submitted_by', $userID, true);
				$item_permalink = get_post_meta($_POST['ID'], 'nomination_permalink', true);
				add_post_meta($newPostID, 'nomination_permalink', $item_permalink, true);
				$item_authorship = get_post_meta($_POST['ID'], 'authors', true);
				add_post_meta($newPostID, 'authors', $item_authorship, true);
				$date_nom = get_post_meta($_POST['ID'], 'date_nominated', true);
				add_post_meta($newPostID, 'date_nominated', $date_nom, true);
				$item_tags = get_post_meta($_POST['ID'], 'item_tags', true);
				add_post_meta($newPostID, 'item_tags', $item_tags, true);
				//If user wants to use tags, we'll create an option to use it.
				$nominators = get_post_meta($_POST['ID'], 'nominator_array', true);
				add_post_meta($newPostID, 'nominator_array', $nominators, true);
				$source_repeat = get_post_meta($_POST['ID'], 'source_repeat', true);
				add_post_meta($newPostID, 'source_repeat', $source_repeat, true);
				$item_feed_post_id = get_post_meta($_POST['ID'], 'item_feed_post_id', true);
				add_post_meta($newPostID, 'item_feed_post_id', $item_feed_post_id, true);

				$already_has_thumb = has_post_thumbnail($_POST['ID']);
				if ($already_has_thumb)  {
					$post_thumbnail_id = get_post_thumbnail_id( $_POST['ID'] );
					set_post_thumbnail($newPostID, $post_thumbnail_id);
				}

			}
		}

	}

	public function get_post_nomination_status($date, $item_id, $post_type, $updateCount = true){
		//Get the query object, limiting by date, type and metavalue ID.
		$postsAfter = pf_get_posts_by_id_for_check( $date, $post_type, $item_id );
		//Assume that it will not find anything.
		$check = false;
		if ($postsAfter):

			global $post;
			foreach ($postsAfter as $post):
				setup_postdata($post);
				$id = get_the_ID();
				$origin_item_id = get_post_meta($id, 'origin_item_ID', true);
				if ($origin_item_id == $item_id) {
					$check = true;
					//Only update the nomination count on request.
					if ($updateCount){
						$nomCount = get_post_meta($id, 'nomination_count', true);
						$nomCount++;
						update_post_meta($id, 'nomination_count', $nomCount);
						$current_user = wp_get_current_user();
						if ( 0 == $current_user->ID ) {
							//Not logged in.
							//If we ever reveal this to non users and want to count nominations by all, here is where it will go.
						} else {
							$nominators = get_post_meta($id, 'nominator_array', true);
							$nominators .= ',' . $current_user->ID;
							update_post_meta($id, 'nominator_array', $nominators);
						}

					return $check;
					break;
					}
				}
			endforeach;
		endif;
		return $check;
	}

	/**
	 * Handles an archive action submitted via AJAX
	 *
	 * @since 1.7
	 */
	public static function archive_a_nom(){
		$pf_drafted_nonce = $_POST['pf_drafted_nonce'];
		if (! wp_verify_nonce($pf_drafted_nonce, 'drafter')){
			die($this->__('Nonce not recieved. Are you sure you should be archiving?', 'pf'));
		} else {
			$current_user = wp_get_current_user();
			$current_user_id = $current_user->ID;
			add_post_meta($_POST['nom_id'], 'archived_by_user_status', 'archived_' . $current_user_id);
			print_r(__('Archived.', 'pf'));
			die();
		}
	}


	public function meta_box_printer($title, $variable, $link = false, $anchor_text = 'Link'){
		echo '<strong>' . $title . '</strong>: ';
		if (empty($variable)){
			echo '<br /><input type="text" name="'.$title.'">';
		} else {		
			if ($link === true){
				if ($anchor_text === 'Link'){
					$anchor_text = $this->__('Link', 'pf');
				}
				echo '<a href=';
				echo $variable;
				echo '" target="_blank">';
				echo $anchor_text;
				echo '</a>';
			} else {
				echo $variable;
			}
		}

		echo '<br />';
	}

	# This and the next few functions are to modify the table that shows up when you click "Nominations".
	function edit_nominations_columns ( $columns ){

		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __('Title', 'pf'),
			'date' => __('Last Modified', 'pf'),
			'nomcount' => __('Nominations', 'pf'),
			'nominatedby' => __('Nominated By', 'pf'),
			'original_author' => __('Original Author', 'pf'),
			'date_nominated' => __('Date Nominated', 'pf')
		);

		return $columns;

	}

	//Via http://slides.helenhousandi.com/wcnyc2012.html#15 and http://svn.automattic.com/wordpress/tags/3.4/wp-admin/includes/class-wp-posts-list-table.php
	function nomination_custom_columns ( $column ) {

		global $post;
		switch ($column) {
			case 'nomcount':
				echo get_post_meta($post->ID, 'nomination_count', true);
				break;
			case 'nominatedby':
				$nominatorID = get_post_meta($post->ID, 'submitted_by', true);
				$user = get_user_by('id', $nominatorID);
				if ( is_a( $user, 'WP_User' ) ) {
					echo $user->display_name;
				}
				break;
			case 'original_author':
				$orig_auth = get_post_meta($post->ID, 'authors', true);
				echo $orig_auth;
				break;
			case 'date_nominated':
				$dateNomed = get_post_meta($post->ID, 'date_nominated', true);
				echo $dateNomed;
				break;


		}
	}

	// Make these columns sortable
	function nomination_sortable_columns() {
	  return array(
		'title' => 'title',
		'date' => 'date',
		'nomcount' => 'nomcount',
		'nominatedby' => 'nominatedby',
		'original_author' => 'original_author',
		'date_nominated' => 'date_nominated'
	  );
	}

	function build_nomination() {

		// Verify nonce
		if ( !wp_verify_nonce($_POST[PF_SLUG . '_nomination_nonce'], 'nomination') )
			die( __( "Nonce check failed. Please ensure you're supposed to be nominating stories.", 'pf' ) );

		if ('' != (get_option('timezone_string'))){	
			date_default_timezone_set(get_option('timezone_string'));	
		}
		//ref http://wordpress.stackexchange.com/questions/8569/wp-insert-post-php-function-and-custom-fields, http://wpseek.com/wp_insert_post/
		$time = current_time('mysql', $gmt = 0);
		//@todo Play with post_exists (wp-admin/includes/post.php ln 493) to make sure that submissions have not already been submitted in some other method.
			//Perhaps with some sort of "Are you sure you don't mean this... reddit style thing?
			//Should also figure out if I can create a version that triggers on nomination publishing to send to main posts.


		//There is some serious delay here while it goes through the database. We need some sort of loading bar.
		ob_start();
		//set up nomination check
		$item_wp_date = $_POST['item_wp_date'];
		$item_id = $_POST['item_id'];
		//die($item_wp_date);

		//Record first nominator and/or add a nomination to the user's count.
		$current_user = wp_get_current_user();
		if ( 0 == $current_user->ID ) {
			//Not logged in.
			$userSlug = "external";
			$userName = __('External User', 'pf');
			$userID = 0;
		} else {
			// Logged in.
			$userID = $current_user->ID;
			if (get_user_meta( $userID, 'nom_count', true )){

				$nom_counter = get_user_meta( $userID, 'nom_count', true );
				$nom_counter++;
				update_user_meta( $userID, 'nom_count', $nom_counter, true );

			} else {
				add_user_meta( $userID, 'nom_count', 1, true );

			}
		}
		$userString = $userID;

		//Going to check posts first on the assumption that there will be more nominations than posts.
		$post_check = $this->get_post_nomination_status($item_wp_date, $item_id, 'post');
		/** The system will only check for nominations of the item does not exist in posts. This will stop increasing the user and nomination count in nominations once they are sent to draft.
		**/
		if ($post_check == true) {
			//Run this function to increase the nomination count in the nomination, even if it is already a post.
			$this->get_post_nomination_status($item_wp_date, $item_id, 'nomination');
			$result = __('This item has already been nominated.', 'pf');
			die($result);
		}
		else {
			$nom_check = $this->get_post_nomination_status($item_wp_date, $item_id, 'nomination');
				if ($nom_check == true) { $result = __('This item has already been nominated', 'pf'); die($result); }
		}


		//set up rest of nomination data
		$item_title = $_POST['item_title'];
		
		$readable_status = get_post_meta($_POST['item_post_id'], 'readable_status', true);
		if ($readable_status != 1){
			$item_content = PF_Readability::readability_object($_POST['item_link']);
			if (!$item_content || ($item_content == 'error-secured')){
				$item_content = htmlspecialchars_decode($_POST['item_content']);
			}
		} else {
			$item_content = htmlspecialchars_decode($_POST['item_content']);
		}

		//No need to define every post arg right? I should only need the ones I'm pushing through. Well, I guess we will find out.
		$data = array(
			'post_status' => 'draft',
			'post_type' => 'nomination',
			//'post_author' => $user_ID,
				//Hurm... what we really need is a way to pass the nominator's userID to this function to credit them as the author of the nomination.
				//Then we could create a leaderboard. ;
			//'post_date' => $_SESSION['cal_startdate'],
				//Do we want this to be nomination date or origonal posted date? Prob. nomination date? Optimally we can store and later sort by both.
			'post_title' => $item_title,//$item_title,
			'post_content' => $item_content,

		);

		$newNomID = wp_insert_post( $data );

		if ($_POST['item_feat_img'] != '')
			PF_Feed_Item::set_ext_as_featured($newNomID, $_POST['item_feat_img']);
		//die($_POST['item_feat_img']);

		add_post_meta($newNomID, 'origin_item_ID', $item_id, true);
		add_post_meta($newNomID, 'nomination_count', 1, true);
		add_post_meta($newNomID, 'submitted_by', $userString, true);
		add_post_meta($newNomID, 'nominator_array', $userID, true);
		add_post_meta($newNomID, 'source_title', $_POST['source_title'], true);
			$item_date = $_POST['item_date'];
			if (empty($_POST['item_date'])){
				$newDate = gmdate('Y-m-d H:i:s');
				$item_date = $newDate;
			}
		add_post_meta($newNomID, 'posted_date', $item_date, true);
		add_post_meta($newNomID, 'authors', $_POST['item_author'], true);
		add_post_meta($newNomID, 'nomination_permalink', $_POST['item_link'], true);
		add_post_meta($newNomID, 'date_nominated', date('c'), true);
		add_post_meta($newNomID, 'item_tags', $_POST['item_tags'], true);
		add_post_meta($newNomID, 'source_repeat', $_POST['source_repeat'], true);
		add_post_meta($newNomID, 'item_feed_post_id', $_POST['item_post_id'], true);
			$response = array(
				'what' => 'nomination',
				'action' => 'build_nomination',
				'id' => $newNomID,
				'data' => $item_title . ' nominated.',
				'supplemental' => array(
					'content' => $item_content,
					'originID' => $item_id,
					'buffered' => ob_get_contents()
				)
			);
			$xmlResponse = new WP_Ajax_Response($response);
			$xmlResponse->send();
		ob_end_flush();
	}

	function build_nom_draft() {
		global $post;
		// verify if this is an auto save routine.
		// If it is our form has not been submitted, so we dont want to do anything
		//if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		$pf_drafted_nonce = $_POST['pf_drafted_nonce'];
		if (! wp_verify_nonce($pf_drafted_nonce, 'drafter')){
			die($this->__('Nonce not recieved. Are you sure you should be drafting?', 'pf'));
		} else {
##Check
		# print_r(__('Sending to Draft.', 'pf'));
##Check
		//print_r($_POST);
		ob_start();
			$item_title = $_POST['nom_title'];
			$item_content = $_POST['nom_content'];
			$data = array(
				'post_status' => 'draft',
				'post_type' => 'post',
				'post_title' => $item_title,
				'post_content' => htmlspecialchars_decode($item_content),
			);
			//Will need to use a meta field to pass the content's md5 id around to check if it has already been posted.

			//We assume that it is already in nominations, so no need to check there. This might be why we can't use post_exists here.
			//No need to origonate the check at the time of the feed item either. It can't become a post with the proper meta if it wasn't a nomination first.
			$item_id = $_POST['item_id'];
			//YYYY-MM-DD
			$nom_date = strtotime($_POST['nom_date']);
			$nom_date = date('Y-m-d', $nom_date);

			//Now function will not update nomination count when it pushes nomination to publication.
			$post_check = $this->get_post_nomination_status($nom_date, $item_id, 'post', false);
			$newPostID = 'repeat';
			//Alternative check with post_exists? or use same as above?
			if ($post_check != true) {
##Check
				//print_r('No Post exists.');
				$newPostID = wp_insert_post( $data, true );
##Check
				//print_r($newPostID);
				add_post_meta($newPostID, 'origin_item_ID', $item_id, true);

				add_post_meta($newPostID, 'source_title', $_POST['source_title'], true);

				add_post_meta($newPostID, 'source_link', $_POST['source_link'], true);

				add_post_meta($newPostID, 'source_slug', $_POST['source_slug'], true);

				$nomCount = $_POST['nom_count'];
				add_post_meta($newPostID, 'nomination_count', $nomCount, true);

				add_post_meta($newPostID, 'nom_id', $_POST['nom_id'], true);

				$nomUserID = $_POST['nom_user'];
				add_post_meta($newPostID, 'submitted_by', $nomUserID, true);

				$item_permalink = $_POST['item_link'];
				add_post_meta($newPostID, 'nomination_permalink', $item_permalink, true);

				$item_authorship = $_POST['item_author'];
				add_post_meta($newPostID, 'authors', $item_authorship, true);

				add_post_meta($newPostID, 'item_date', $_POST['item_date'], true);

				add_post_meta($newPostID, 'item_link', $_POST['item_link'], true);

				$date_nom = $_POST['nom_date'];
				add_post_meta($newPostID, 'date_nominated', $date_nom, true);

				add_post_meta($newPostID, 'nom_count', $_POST['nom_count'], true);

				$item_tags = $_POST['nom_tags'];
				add_post_meta($newPostID, 'item_tags', $item_tags, true);

				//If user wants to use tags, we'll create an option to use it.
				$nominators = $_POST['nom_users'];
				add_post_meta($newPostID, 'nominator_array', $nominators, true);

				$already_has_thumb = has_post_thumbnail($_POST['nom_id']);
				if ($already_has_thumb)  {
					$post_thumbnail_id = get_post_thumbnail_id( $_POST['nom_id'] );
					set_post_thumbnail($newPostID, $post_thumbnail_id);
				}

			}
			$response = array(
				'what' => 'draft',
				'action' => 'build_nom_draft',
				'id' => $newPostID,
				'data' => $item_title . ' drafted.',
				'supplemental' => array(
					'content' => $item_content,
					'originID' => $item_id,
					'repeat' => $post_check,
					'buffered' => ob_get_contents()
				)
			);
			$xmlResponse = new WP_Ajax_Response($response);
			$xmlResponse->send();
			ob_end_flush();
		}
	}


}
