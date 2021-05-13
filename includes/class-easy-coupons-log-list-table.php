<?php

/**
 * The file that defines Data table List
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
 * The data table class.
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

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Easy_Coupons_Log_List_Table extends WP_List_Table {

    private $databaseTable;
	/**
	 * [REQUIRED] You must declare constructor and give some basic params
	 */
	function __construct() {
		global $status, $page;

		parent::__construct( [
			'singular' => 'coupon',
			'plural'   => 'coupons',
            'ajax'      => false
		] );

        $this->databaseTable = 'easy_coupon_logs';
	}

	/**
	 * [REQUIRED] this is a default column renderer
	 *
	 * @param $item - row (key, value array)
	 * @param $column_name - string (key)
	 * @return HTML
	 */
	function column_default( $item, $column_name ) {
		return $item[$column_name];
	}

	/**
	 * [OPTIONAL] this is example, how to render specific column
	 *
	 * method name must be like this: "column_[column_name]"
	 *
	 * @param $item - row (key, value array)
	 * @return HTML
	 */
	function column_status( $item ) {
        if($item['status'] == 1){
            $val = '<span style="color:#00a32a;">Valid</span>';
        }elseif($item['status'] == 2){
            $val = '<span style="color:#b32d2e;">Already Used</span>';
        }else{
            $val = '<span style="color:#b32d2e;">Not Found</span>';
        }
		return '<b>' . $val . '</b>';
	}

	/**
	 * [OPTIONAL] this is example, how to render column with actions,
	 * when you hover row "Edit | Delete" links showed
	 *
	 * @param $item - row (key, value array)
	 * @return HTML
	 */
	function column_coupon( $item ) {

		return sprintf( '<span class="row-title">%s</span>',
			$item['coupon']
		);
	}

    /**
	 * [REQUIRED] this is how checkbox column renders
	 *
	 * @param $item - row (key, value array)
	 * @return HTML
	 */
	function column_actions( $item ) {
        $actions = [
			'delete' => sprintf( '<a href="%s">%s</a>', add_query_arg( array( 'page' => $_REQUEST['page'], 'action' => 'delete', '_wpnonce' => wp_create_nonce( 'delete_action_nonce' ), 'id' => $item['id'] ), admin_url( "admin.php" ) ), __( 'Delete', 'cltd_example' ) ),
		];

        //$actions['trash'] = '<a data-trash="yes" href="' . add_query_arg( array( 'page' => $_REQUEST['page'], 'action' => 'delete', '_wpnonce' => wp_create_nonce( 'delete_action_nonce' ), 'id' => $item['id'] ), admin_url( "admin.php" ) ) . '">' . __( 'Delete', WP_Statistics_Actions::$textdomain ) . '</a>';

		return sprintf(
			'%s',
			$this->row_actions( $actions,true )
		);
	}

	/**
	 * [REQUIRED] this is how checkbox column renders
	 *
	 * @param $item - row (key, value array)
	 * @return HTML
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="id[]" value="%s" />',
			$item['id']
		);
	}

	/**
	 * [REQUIRED] This method return columns to display in table
	 * you can skip columns that you do not want to show
	 * like content, or description
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb'    => '<input type="checkbox" />', //Render a checkbox instead of text
			'coupon'  => __( 'Coupon', 'cltd_example' ),
			'video_title' => __( 'Video Title', 'cltd_example' ),
			'status'   => __( 'Type', 'cltd_example' ),
			'created_at'   => __( 'Date Time', 'cltd_example' ),
			'actions'   => __( 'Actions', 'cltd_example' ),
		];
		return $columns;
	}

	/**
	 * [OPTIONAL] This method return columns that may be used to sort table
	 * all strings in array - is column names
	 * notice that true on name column means that its default sort
	 *
	 * @return array
	 */
	function get_sortable_columns() {
		$sortable_columns = [
			'video_title'  => [ 'video_title', false ],
			'status'  => [ 'status', false ],
			'created_at' => [ 'created_at', false ],
		];
		return $sortable_columns;
	}

	/**
	 * [OPTIONAL] Return array of bult actions if has any
	 *
	 * @return array
	 */
	function get_bulk_actions() {
		$actions = [
			'delete' => 'Delete',
		];
		return $actions;
	}


    /** Text displayed when no customer data is available */
    public function no_items() {
        return _e( 'No coupon uses history avaliable.', 'sp' );
    }

	/**
	 * [OPTIONAL] This method processes bulk actions
	 * it can be outside of class
	 * it can not use wp_redirect coz there is output already
	 * in this example we are processing delete action
	 * message about successful deletion will be shown on page in next part
	 */
	function process_bulk_action() {
		global $wpdb;
		$table_name = $wpdb->prefix . $this->databaseTable; // do not forget about tables prefix

		if ( 'delete' === $this->current_action() ) {
			$ids = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : [];
			if ( is_array( $ids ) ) {
				$ids = implode( ',', $ids );
			}

			if ( ! empty( $ids ) ) {
				$wpdb->query( "DELETE FROM $table_name WHERE id IN($ids)" );
			}
		}

        if ( ( isset( $_REQUEST['delete-by-date'] ) && $_REQUEST['delete-by-date'] !== '')) {
            $date = isset( $_REQUEST['delete-by-date'] ) ? $_REQUEST['delete-by-date'] : '';

            if ( ! empty( $date ) ) {
				$wpdb->query( "DELETE FROM $table_name WHERE DATE(created_at)='$date'" );
			}
        }
	}


 	/**
	 * Add extra markup in the toolbars before or after the list
	 * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
	 */
	function extra_tablenav( $which ) {
		$search = @$_POST['date']?esc_attr($_POST['date']):"";
		if ( $which == "top" ) : ?>
		<div class="alignleft actions bulkactions">
			<p class="search-box">
				<label for="post-search-input" class="screen-reader-text">Search Pages:</label>
				<input type="date" value="<?php echo $search; ?>" max="<?php echo date('Y-m-d');?>" name="delete-by-date" id="post-search-input">
				<!-- <input type="hidden" value="1" name="delete-by-date" id="post-search-input"> -->
				<input type="submit" value="Find & Delete" class="button" id="search-submit" name="">
			</p>
		</div>
		<?php endif;
	}

	/**
	 * [REQUIRED] This is the most important method
	 *
	 * It will get rows from database and prepare them to be showed in table
	 */
	function prepare_items() {
		global $wpdb;
		$table_name = $wpdb->prefix . $this->databaseTable; // do not forget about tables prefix

		// constant, how much records will be shown per page
        $per_page = $this->get_items_per_page( 'coupons_per_page', 25 );


		$columns  = $this->get_columns();
		$hidden   = [];
		$sortable = $this->get_sortable_columns();

		// here we configure table headers, defined in our methods
		$this->_column_headers = [ $columns, $hidden, $sortable ];

		// [OPTIONAL] process bulk action if any
		$this->process_bulk_action();

		// will be used in pagination settings
		$total_items = $wpdb->get_var( "SELECT COUNT(id) FROM $table_name" );

		// prepare query params, as usual current page, order by and order direction
		$paged   = isset( $_REQUEST['paged'] ) ? max( 0, intval( $_REQUEST['paged'] - 1 ) * $per_page ) : 0;

        $sql = "SELECT * FROM $table_name";

        if( ! empty( $_REQUEST['s'] ) ){
                        $search = esc_sql( $_REQUEST['s'] );
            $sql .= " WHERE coupon LIKE '%{$search}%'";
        }

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . $paged;

		$this->items = $wpdb->get_results( $sql, ARRAY_A );

		// [REQUIRED] configure pagination
		$this->set_pagination_args( [
			'total_items' => $total_items, // total items defined above
			'per_page'    => $per_page, // per page constant defined at top of method
			'total_pages' => ceil( $total_items / $per_page ), // calculate pages count
		] );
	}
}