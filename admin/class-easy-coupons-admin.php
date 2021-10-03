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
class Easy_Coupons_Admin
{

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
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_action('admin_menu', [$this, 'admin_pages']);
		add_action('init', [$this, 'create_easyvideo_cpt'], 0);

		// Sidebar - before Metaboxs - Pages.
		add_action('submitpost_box', [$this, 'callback__submitpost_box']);


		// Add the custom columns to the Video post type:
		add_filter('manage_easy-video_posts_columns', [$this, 'set_custom_edit_easy_video_columns']);

		// Add the data to the custom columns for the book post type:
		add_action('manage_easy-video_posts_custom_column', [$this, 'custom_easy_video_column'], 10, 2);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/easy-coupons-admin.css', [], $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/easy-coupons-admin.js', ['jquery'], $this->version, false);
	}

	public function admin_pages()
	{
		$hook = add_menu_page('Easy Coupons', 'Easy Coupons', 'manage_options', 'easy-coupons', [$this, 'all_coupons_page_callback']);
		//add_action('load-' . $hook, [$this,'add_screen_options']);
		add_submenu_page(
			'easy-coupons',
			'All Coupons',
			'All Coupons',
			'manage_options',
			'easy-coupons'
		);
		add_submenu_page('easy-coupons', __('Add new', 'easy-coupons'), __('Add new', 'easy-coupons'), 'activate_plugins', 'new-coupon', [$this, 'generate_coupons_page_callback']);
		add_submenu_page(
			'easy-coupons',
			'Access log',
			'Access log',
			'manage_options',
			'easy-coupons-log',
			[$this, 'coupons_activity_page_callback']
		);
	}

	/**
	 * Display a custom menu page
	 */
	public function all_coupons_page_callback()
	{
		$list_table = new Easy_Coupons_List_Table();

		$list_table->prepare_items();
		echo '<div class="wrap">
			<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
			<h2>', __('Easy Coupons', 'easy-coupons'), '
				<a class="add-new-h2" href="', get_admin_url(get_current_blog_id(), 'admin.php?page=new-coupon'), '">', __('Add new', 'easy-coupons'), '</a>
			</h2>

			<form id="persons-table" method="post">
				<input type="hidden" name="page" value="easy-coupons" />';
		$list_table->search_box('Search', 'search');
		$list_table->display();
		echo '</form>

		</div>';
	}

	public function generate_coupons_page_callback()
	{
		$page = new Easy_Coupons_Generator();
		$page->new_coupon_form();
	}

	public function coupons_activity_page_callback()
	{
		$list_table = new Easy_Coupons_Log_List_Table();

		$list_table->prepare_items();
		echo '<div class="wrap">
			<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
			<h2>', __('Easy Coupons Uses Log', 'easy-coupons'), '</h2>

			<form id="persons-table" method="post">
				<input type="hidden" name="page" value="easy-coupons" />';
		$list_table->search_box('Search', 'search');
		$list_table->display();
		echo '</form>

		</div>';
	}

	// Register Custom Post Type Easy Video
	function create_easyvideo_cpt()
	{

		$labels = array(
			'name' => _x('Easy Videos', 'Post Type General Name', 'easy-coupons'),
			'singular_name' => _x('Easy Video', 'Post Type Singular Name', 'easy-coupons'),
			'menu_name' => _x('Easy Videos', 'Admin Menu text', 'easy-coupons'),
			'name_admin_bar' => _x('Easy Video', 'Add New on Toolbar', 'easy-coupons'),
			'archives' => __('Easy Video Archives', 'easy-coupons'),
			'attributes' => __('Easy Video Attributes', 'easy-coupons'),
			'parent_item_colon' => __('Parent Easy Video:', 'easy-coupons'),
			'all_items' => __('All Easy Videos', 'easy-coupons'),
			'add_new_item' => __('Add New Easy Video', 'easy-coupons'),
			'add_new' => __('Add New', 'easy-coupons'),
			'new_item' => __('New Easy Video', 'easy-coupons'),
			'edit_item' => __('Edit Easy Video', 'easy-coupons'),
			'update_item' => __('Update Easy Video', 'easy-coupons'),
			'view_item' => __('View Easy Video', 'easy-coupons'),
			'view_items' => __('View Easy Videos', 'easy-coupons'),
			'search_items' => __('Search Easy Video', 'easy-coupons'),
			'not_found' => __('Not found', 'easy-coupons'),
			'not_found_in_trash' => __('Not found in Trash', 'easy-coupons'),
			'featured_image' => __('Featured Image', 'easy-coupons'),
			'set_featured_image' => __('Set featured image', 'easy-coupons'),
			'remove_featured_image' => __('Remove featured image', 'easy-coupons'),
			'use_featured_image' => __('Use as featured image', 'easy-coupons'),
			'insert_into_item' => __('Insert into Easy Video', 'easy-coupons'),
			'uploaded_to_this_item' => __('Uploaded to this Easy Video', 'easy-coupons'),
			'items_list' => __('Easy Videos list', 'easy-coupons'),
			'items_list_navigation' => __('Easy Videos list navigation', 'easy-coupons'),
			'filter_items_list' => __('Filter Easy Videos list', 'easy-coupons'),
		);
		$args = array(
			'label' => __('Easy Video', 'easy-coupons'),
			'description' => __('', 'easy-coupons'),
			'labels' => $labels,
			'menu_icon' => 'dashicons-video-alt3',
			'supports' => array('title', 'thumbnail'),
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
		register_post_type('easy-video', $args);
	}


	/**
	 * @param $post
	 */
	public function callback__submitpost_box($post)
	{
		if ('easy-video' === $post->post_type && 'publish' === $post->post_status) {
			echo '<div class="sc-box">
			<div class="postbox-header"><h2 class="hndle ui-sortable-handle">'.__('Video Shortcode','easy-coupons').'</h2></div>
			<div class="inside">
			<input type="text" value="[easyvid id=', esc_attr($post->ID), ']" readonly>
			<p>'.__('Use this shortcode to render a locked video','easy-coupons').'</p>
			</div>
			</div>';
		}
	}


	/**
	 * Undocumented function
	 *
	 * @param Array $columns
	 * @return void
	 */
	public function set_custom_edit_easy_video_columns($columns)
	{
		$takeout_date = $columns['date'];
		unset($columns['date']);
		$columns['short_code'] = __('Short Code', 'easy-coupons');
		$columns['date'] = $takeout_date;
		return $columns;
	}


	/**
	 * Undocumented function
	 *
	 * @param String $column
	 * @param String|Integer $post_id
	 * @return void
	 */
	public function custom_easy_video_column($column, $post_id)
	{
		switch ($column) {
			case 'short_code':
				echo '<input type="text" value="[easyvid id=', esc_attr($post_id), ']" readonly>';
				break;
		}
	}
}
