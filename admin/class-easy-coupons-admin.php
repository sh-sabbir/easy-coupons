<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://iamsabbir.dev
 * @since      1.0.0
 *
 * @package    Easy_Coupons
 * @subpackage Easy_Coupons/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Easy_Coupons
 * @subpackage Easy_Coupons/admin
 * @author     Sabbir Hasan <sabbirshouvo@gmail.com>
 */
class Easy_Coupons_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	public $list_table;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_action( 'admin_menu', [$this, 'admin_pages'] );
		add_action( 'init', [$this,'create_easyvideo_cpt'], 0 );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Easy_Coupons_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Easy_Coupons_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/easy-coupons-admin.css', [], $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Easy_Coupons_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Easy_Coupons_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/easy-coupons-admin.js', ['jquery'], $this->version, false );

	}

	public function admin_pages() {
		//add_menu_page( 'CRUD', 'CRUD', 'manage_options', __FILE__, 'crudAdminPage', 'dashicons-wordpress' );

		$hook = add_menu_page( 'Easy Coupons', 'Easy Coupons', 'manage_options', 'easy-coupons', [ $this, 'my_custom_menu_page' ] );
		//add_action('load-' . $hook, [$this,'add_screen_options']);
		add_submenu_page( 'easy-coupons', 'All Coupons', 'All Coupons',
			'manage_options', 'easy-coupons' );
		add_submenu_page( 'easy-coupons', __( 'Add new', 'cltd_example' ), __( 'Add new', 'cltd_example' ), 'activate_plugins', 'new-coupon', [$this, 'my_custom_menu_page3'] );
		add_submenu_page( 'easy-coupons', 'Access log', 'Access log',
			'manage_options', 'easy-coupons-log', [ $this, 'my_custom_menu_page2' ] );
	}

	/**
	 * Display a custom menu page
	 */
	public function my_custom_menu_page() {
		$list_table = new Easy_Coupons_List_Table();
		
		$list_table->prepare_items();
		echo '<div class="wrap">
			<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
			<h2>',__( 'Easy Coupons', 'cltd_example' ),'
				<a class="add-new-h2" href="',get_admin_url( get_current_blog_id(), 'admin.php?page=new-coupon'), '">',__( 'Add new', 'cltd_example' ),'</a>
			</h2>

			<form id="persons-table" method="post">
				<input type="hidden" name="page" value="easy-coupons" />';
					$list_table->search_box('Search', 'search');
					$list_table->display(); 
			echo '</form>

		</div>';
	}

	public function my_custom_menu_page2() {
		$list_table = new Easy_Coupons_Log_List_Table();
		
		$list_table->prepare_items();
		echo '<div class="wrap">
			<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
			<h2>',__( 'Easy Coupons Uses Log', 'cltd_example' ),'</h2>

			<form id="persons-table" method="post">
				<input type="hidden" name="page" value="easy-coupons" />';
					$list_table->search_box('Search', 'search');
					$list_table->display(); 
			echo '</form>

		</div>';
	}

	public function my_custom_menu_page3() {
		$page = new Easy_Coupons_Generator();

		$page->new_coupon_form();
	}


	// not working
	// function add_screen_options() {

	// 	$option = 'per_page';
	// 	$args = [
	// 		'label' => 'Coupons',
	// 		'default' => 25,
	// 		'option' => 'coupons_per_page'
	// 	];

	// 	add_screen_option( $option, $args );

	// 	$this->list_table = new Easy_Coupons_List_Table();
	// }


	// Register Custom Post Type Easy Video
	function create_easyvideo_cpt() {

		$labels = array(
			'name' => _x( 'Easy Videos', 'Post Type General Name', 'textdomain' ),
			'singular_name' => _x( 'Easy Video', 'Post Type Singular Name', 'textdomain' ),
			'menu_name' => _x( 'Easy Videos', 'Admin Menu text', 'textdomain' ),
			'name_admin_bar' => _x( 'Easy Video', 'Add New on Toolbar', 'textdomain' ),
			'archives' => __( 'Easy Video Archives', 'textdomain' ),
			'attributes' => __( 'Easy Video Attributes', 'textdomain' ),
			'parent_item_colon' => __( 'Parent Easy Video:', 'textdomain' ),
			'all_items' => __( 'All Easy Videos', 'textdomain' ),
			'add_new_item' => __( 'Add New Easy Video', 'textdomain' ),
			'add_new' => __( 'Add New', 'textdomain' ),
			'new_item' => __( 'New Easy Video', 'textdomain' ),
			'edit_item' => __( 'Edit Easy Video', 'textdomain' ),
			'update_item' => __( 'Update Easy Video', 'textdomain' ),
			'view_item' => __( 'View Easy Video', 'textdomain' ),
			'view_items' => __( 'View Easy Videos', 'textdomain' ),
			'search_items' => __( 'Search Easy Video', 'textdomain' ),
			'not_found' => __( 'Not found', 'textdomain' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'textdomain' ),
			'featured_image' => __( 'Featured Image', 'textdomain' ),
			'set_featured_image' => __( 'Set featured image', 'textdomain' ),
			'remove_featured_image' => __( 'Remove featured image', 'textdomain' ),
			'use_featured_image' => __( 'Use as featured image', 'textdomain' ),
			'insert_into_item' => __( 'Insert into Easy Video', 'textdomain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Easy Video', 'textdomain' ),
			'items_list' => __( 'Easy Videos list', 'textdomain' ),
			'items_list_navigation' => __( 'Easy Videos list navigation', 'textdomain' ),
			'filter_items_list' => __( 'Filter Easy Videos list', 'textdomain' ),
		);
		$args = array(
			'label' => __( 'Easy Video', 'textdomain' ),
			'description' => __( '', 'textdomain' ),
			'labels' => $labels,
			'menu_icon' => 'dashicons-video-alt3',
			'supports' => array('title','thumbnail', 'custom-fields'),
			'taxonomies' => array(),
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 100,
			'show_in_admin_bar' => false,
			'show_in_nav_menus' => false,
			'can_export' => true,
			'has_archive' => false,
			'hierarchical' => false,
			'exclude_from_search' => true,
			'show_in_rest' => false,
			'publicly_queryable' => false,
			'capability_type' => 'post',
		);
		register_post_type( 'easy-video', $args );

	}
}
