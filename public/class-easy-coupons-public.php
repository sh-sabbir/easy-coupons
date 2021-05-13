<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://iamsabbir.dev
 * @since      1.0.0
 *
 * @package    Easy_Coupons
 * @subpackage Easy_Coupons/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Easy_Coupons
 * @subpackage Easy_Coupons/public
 * @author     Sabbir Hasan <sabbirshouvo@gmail.com>
 */
class Easy_Coupons_Public {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_shortcode( 'easyvid', [$this, 'easyvid_callback'] );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/easy-coupons-public.css', [], $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	/**
	 * @param $atts
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/easy-coupons-public.js', [ 'jquery' ], $this->version, false );

		wp_localize_script( $this->plugin_name, 'extra',
			array( 
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	function easyvid_callback( $atts ) {
		$atts = shortcode_atts( [
			'id' => '',
		], $atts, 'easyvid' );

		$vid_id    = $atts['id'];
		$vid_title = get_the_title($vid_id);
		$vid_url   = get_post_meta($vid_id, 'video', true);
		$vid_poster= get_the_post_thumbnail_url($vid_id, 'large');

		//Check if already unlocked
		$unlocked_list = urldecode($_COOKIE['unlocked_vids']);
		$unlocked_list = stripslashes($unlocked_list);
		$unlocked_list = json_decode($unlocked_list,true);

		//Render
		$output = "<div id='easyvid-{$vid_id}' class='easyvid'>";
		$output .= "<h2 class='easyvid-title'>{$vid_title}</h2>";
		$output .= "<div class='vidcontainer'>";
		if(!null == $unlocked_list && in_array($vid_id,$unlocked_list)){
			$output .= "<iframe class='responsive-iframe' src='{$vid_url}'></iframe>";
		}else{
			$output .= "<img class='responsive-iframe' src='{$vid_poster}'/>";
			$output .= "<span data-easyvid data-easyvid-id='{$vid_id}' class='unlock'>Unlock Video</span>";
		}
		$output .= "</div>";
		$output .= "</div>";

		return $output;
	}

}
