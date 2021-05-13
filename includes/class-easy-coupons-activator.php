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
class Easy_Coupons_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// Create the teams table.
		$table_name     = $wpdb->prefix . 'easy_coupon';
		$table_name_log = $wpdb->prefix . 'easy_coupon_logs';

		$sql = "CREATE TABLE $table_name (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					coupon varchar(55) NOT NULL,
					expiry_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					is_used smallint(2) NOT NULL,
					created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					PRIMARY KEY (id),
					UNIQUE (coupon)
					) $charset_collate;";

		$sql .= "CREATE TABLE $table_name_log (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					coupon varchar(55) NOT NULL,
					status smallint(2) NOT NULL,
					video_id mediumint(2) NOT NULL,
					video_title text DEFAULT NULL,
					created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					PRIMARY KEY (id)
					) $charset_collate;";

		dbDelta( $sql );
	}

	public function seed_database() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'easy_coupon'; // do not forget about tables prefix
		$wpdb->insert( $table_name, [
			'coupon'  => 'f52s',
			'expiry_date' => new DateTime(),
			'is_used'   => 25,
			'created_at' => new DateTime()
		] );
		$wpdb->insert( $table_name, [
			'coupon'  => 's51q',
			'expiry_date' => new DateTime(),
			'is_used'   => 25,
			'created_at' => new DateTime()
		] );
	}

}
