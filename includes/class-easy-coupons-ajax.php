<?php

/**
 * Fired during plugin activation
 *
 * @link       https://iamsabbir.dev
 * @since      1.0.0
 *
 * @package    Easy_Coupons
 * @subpackage Easy_Coupons/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Easy_Coupons
 * @subpackage Easy_Coupons/includes
 * @author     Sabbir Hasan <sabbirshouvo@gmail.com>
 */
class Easy_Coupons_Ajax {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		add_action( 'wp_ajax_unlock_a_vid', [$this,'easyvid_unlock_func'] );
		add_action( 'wp_ajax_nopriv_unlock_a_vid', [$this,'easyvid_unlock_func'] );
	}

	function easyvid_unlock_func(){
		$vid_id = $_REQUEST['vid_id'];
		$coupon = $_REQUEST['coupon'];
		
		$status = $this->check_coupon($coupon);

		if(1 === $status){
			$this->set_unlocked($vid_id);

			echo get_post_meta($vid_id, 'video', true);
		}elseif(2 === $status){
			echo "code_used";
		}else{
			echo "code_invalid";
		}
		
		$this->log_entry($status, $coupon, $vid_id);
		die();
	}

	function set_unlocked($vid_id){
		if(isset($_COOKIE['unlocked_vids'])) {
			$prev_value = urldecode($_COOKIE['unlocked_vids']);
			$prev_value = stripslashes($prev_value);
			$prev_value = json_decode($prev_value,true);
			if(!in_array($vid_id, $prev_value)){
				array_push($prev_value, $vid_id);
			}
			$new_value = json_encode($prev_value);
			setcookie('unlocked_vids', $new_value, time() + (86400 * 30), "/");
		}else{
			$init_value = array($vid_id);
			$init_value = json_encode($init_value);
			setcookie('unlocked_vids', $init_value, time() + (86400 * 30), "/");
		}
	}

	function check_coupon($coupon){
		global $wpdb;
		$table = $wpdb->prefix . 'easy_coupon';

		$log_sts = 0;

		$has_coupon = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE coupon = '$coupon'"));

		if($has_coupon){
			$status = $has_coupon->is_used;

			if(0 === absint($status)){
				$log_sts = 1;
				$this->coupon_use($coupon);
			}else{
				$log_sts = 2;
			}
		}else{
			$log_sts = 3;
		}

		return $log_sts;
	}

	function coupon_use($code){
		global $wpdb;
		$table = $wpdb->prefix . 'easy_coupon';

		$wpdb->update( $table, array( 'is_used' => 1),array('coupon'=>$code));
	}

	function log_entry($status, $coupon, $vid_id){
		global $wpdb;
		$table_name = $wpdb->prefix . 'easy_coupon_logs';

		$vid_title = get_the_title($vid_id);
		
		$item  = array(
            'coupon' => $coupon,
            'status' => $status,
            'video_id' => $vid_id,
            'video_title' => $vid_title,
            'created_at' => date('Y-m-d H:i:s'),
        );

		$format = array('%s','%d','%d','%s','%s');

		
		$wpdb->insert($table_name, $item, $format);
		
	}
}
