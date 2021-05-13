<?php
/**
 * Handles Coupon Generation.
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
 * Handles Coupon Generation.
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

class Easy_Coupons_Generator{

    private $database_table;

    public function __construct() {
        global $wpdb;
		$this->database_table = $wpdb->prefix . 'easy_coupon';
	}

    function new_coupon_form()
    {
        global $wpdb;
        $table_name = $this->database_table; // do not forget about tables prefix

        $message = '';
        $notice = '';

        // this is default $item which will be used for new records
        $default = array(
            // 'id' => 0,
            'coupon' => '',
            'expiry_date' => '',
            'is_used' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        );

        // here we are verifying does this request is post back and have correct nonce
        if ( isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {

            $generated = 0;
            $target = $_REQUEST['code_count'];
            $expire = $_REQUEST['expire_date'];

            while ($generated < $target) {
                $item = $default;

                $item['coupon'] = $this->generate_code();
                $date = new DateTime($expire);
                $item['expiry_date'] = date('Y-m-d H:i:s', $date->getTimestamp());
                
                $result = $wpdb->insert($table_name, $item);
                if ($result) {
                    $generated++;
                }
                
                if ($generated == $target) {
                    $message = $target.' coupon code generated!';
                }
            }
        } else {
            // if this is not post back we load item to edit or give new one to create
            $item = $default;
            if (isset($_REQUEST['id'])) {
                $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
                if (!$item) {
                    $item = $default;
                    $notice = __('Item not found', 'easy-coupons');
                }
            }
        }

        // here we adding our custom meta box
        add_meta_box('coupons_form_meta_box', 'Bulk Coupon Code Generator', [$this, 'easy_coupons_form_meta_box_handler'], 'new-coupon', 'normal', 'default');

        ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
        <h2><?php _e('Easy Coupons', 'easy-coupons')?>
        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=easy-coupons');?>"><?php _e('Back to list', 'easy-coupons')?></a>
        </h2>

        <?php if (!empty($notice)): ?>
        <div id="notice" class="error"><p><?php echo $notice ?></p></div>
        <?php endif;?>
        <?php if (!empty($message)): ?>
        <div id="message" class="updated"><p><?php echo $message ?></p></div>
        <?php endif;?>

        <form id="form" method="POST">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
            <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
            <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

            <div class="metabox-holder" id="poststuff">
                <div id="post-body">
                    <div id="post-body-content">
                        <?php /* And here we call our custom meta box */ ?>
                        <?php do_meta_boxes('new-coupon', 'normal', $item); ?>
                        <input type="submit" value="<?php _e('Save', 'easy-coupons')?>" id="submit" class="button-primary" name="submit">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
    }

    /**
     * This function renders our custom meta box
     * $item is row
     *
     * @param $item
     */
    function easy_coupons_form_meta_box_handler($item)
    {
        ?>

    <table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
        <tbody>
        <tr class="form-">
            <th valign="top" scope="row">
                <label for="code_count"><?php _e('Number of Coupon', 'easy-coupons')?></label>
            </th>
            <td>
                <input id="code_count" name="code_count" type="number" min="1" max="100" value="1"
                    class="small-text" required>
            </td>
        </tr>
        <tr class="form-">
            <th valign="top" scope="row">
                <label for="expire_date"><?php _e('Expiry Date', 'easy-coupons')?></label>
            </th>
            <td>
                <input id="expire_date" name="expire_date" type="date" class="regular-text" min="<?php echo date('Y-m-d'); ?>" required>
            </td>
        </tr>
        </tbody>
    </table>
    <?php
    }

    /**
     * This function generates coupon codes.
     *
     */
    private function generate_code(){
        $bytes = random_bytes(2);
        // var_dump(bin2hex($bytes));
        return bin2hex($bytes);
    }
}