<?php
/**
 * The file that defines Custom Post Type MetaBox
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://iamsabbir.dev
 * @since      1.0.0
 *
 * @package    Easy_Coupons
 * @subpackage Easy_Coupons/includes
 */

/**
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Easy_Coupons
 * @subpackage Easy_Coupons/includes
 * @author     Sabbir Hasan <sabbirshouvo@gmail.com>
 */

class Eazy_Video_Metabox {

	/**
	 * @var array
	 */
	private $screens = ['easy-video'];

	/**
	 * @var array
	 */
	private $fields = [
		[
			'label'   => 'Video Url',
			'id'      => 'video',
			'type'    => 'url',
			'default' => 'https://www.youtube.com/embed/<video-id>',
		],
	];

	public function __construct() {
		add_action( 'add_meta_boxes', [$this, 'add_meta_boxes'] );
		add_action( 'save_post', [$this, 'save_fields'] );
	}

	public function add_meta_boxes() {
		add_meta_box(
            'LockedVideo',
            __( 'Locked Video', 'easy-coupons' ),
            [$this, 'meta_box_callback'],
            'easy-video',
            'normal',
            'default'
        );
	}

	/**
	 * @param $post
	 */
	public function meta_box_callback( $post ) {
		wp_nonce_field( 'LockedVideo_data', 'LockedVideo_nonce' );
		$this->field_generator( $post );
	}

	/**
	 * @param $post
	 */
	public function field_generator( $post ) {
		$output = '';
		foreach ( $this->fields as $field ) {
			$label      = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
			$meta_value = get_post_meta( $post->ID, $field['id'], true );
			if ( empty( $meta_value ) ) {
				if ( isset( $field['default'] ) ) {
					$meta_value = $field['default'];
				}
			}
			switch ( $field['type'] ) {
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">', 'color' !== $field['type'] ? 'style="width: 100%"' : '',
						$field['id'],
						$field['id'],
						$field['type'],
						$meta_value
					);
			}
			$output .= $this->format_rows( $label, $input );
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}

	/**
	 * @param $label
	 * @param $input
	 */
	public function format_rows( $label, $input ) {
		return '<div style="margin-top: 10px;"><strong>' . $label . '</strong></div><div>' . $input . '</div>';
	}

	/**
	 * @param $post_id
	 * @return mixed
	 */
	public function save_fields( $post_id ) {
		if ( ! isset( $_POST['LockedVideo_nonce'] ) ) {
			return $post_id;
		}
		$nonce = $_POST['LockedVideo_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'LockedVideo_data' ) ) {
			return $post_id;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		foreach ( $this->fields as $field ) {
			if ( isset( $_POST[$field['id']] ) ) {
				switch ( $field['type'] ) {
					case 'url':
						$_POST[$field['id']] = esc_url_raw( $_POST[$field['id']] );
						break;
					case 'text':
						$_POST[$field['id']] = sanitize_text_field( $_POST[$field['id']] );
						break;
				}
				update_post_meta( $post_id, $field['id'], $_POST[$field['id']] );
			} else if ( 'checkbox' === $field['type'] ) {
				update_post_meta( $post_id, $field['id'], '0' );
			}
		}
	}

}

if ( class_exists( 'Eazy_Video_Metabox' ) ) {
	new Eazy_Video_Metabox();
}
