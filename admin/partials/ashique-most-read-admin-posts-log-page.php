<?php 

class Post_log_table extends WP_List_Table
{
    // define $table_data property
    private $table_data;

    // Define table columns
    public function get_columns() {
        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'pid'           => __('Post ID', ASHIQUE_MOST_READ_POST_TEXTDOMAIN),
            'title'         => __('Post Title', ASHIQUE_MOST_READ_POST_TEXTDOMAIN),
            'read_date'     => __('Read Date', ASHIQUE_MOST_READ_POST_TEXTDOMAIN),
            'read_counter'  => __('Read Count', ASHIQUE_MOST_READ_POST_TEXTDOMAIN)
        );

        return $columns;
    }

    // Column default value
    public function column_default($item, $column_name)
    {
          switch ($column_name) {
                case 'pid':
                    return $item['post_id'];
                case 'title':
                    return '<a href="'. get_the_permalink( $item['post_id'] ) .'">' . get_the_title( $item['post_id'] ) . '</a>';
                case 'read_date':
                    return $item[$column_name];
                case 'read_counter':
                    return $item[$column_name];
                default:
                    return $item[$column_name];
          }
    }

    // Bind table with columns, data and all
    function prepare_items() {
        //data
        $this->table_data = $this->get_table_data();

        $columns    = $this->get_columns();
        $hidden     = array();
        $sortable   = array();
        $this->_column_headers = array($columns, $hidden, $sortable);

        /* pagination */
        $per_page       = 30;
        $current_page   = $this->get_pagenum();
        $total_items    = count($this->table_data);

        $this->table_data = array_slice( $this->table_data, ( ( $current_page - 1 ) * $per_page ), $per_page );

        $this->set_pagination_args( 
            array(
                'total_items' => $total_items, // total number of items
                'per_page'    => $per_page, // items to show on a page
                'total_pages' => ceil( $total_items / $per_page ) // use ceil to round up
            ) 
        );
        
        $this->items = $this->table_data;
    }

    // Get table data
    private function get_table_data() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'ashique_most_read_posts';

        // How many posts to show
		$most_read_days_number = get_option( 'most_read_days_number', 7 );

        $table_sql = "SELECT post_id, MAX(read_date) as read_date, SUM(read_counter) as read_counter  
            FROM $table_name 
            WHERE 
            read_date >= DATE(NOW() - INTERVAL %d DAY)  
            GROUP BY post_id 
            ORDER BY read_counter DESC";

        $sql = $wpdb->prepare($table_sql, $most_read_days_number);

		return $wpdb->get_results($sql, ARRAY_A);
    }
}

// Creating an instance
$table = new Post_log_table();

$admin_settings_page_url = admin_url( 'options-general.php?page=most-read-posts-settings' );

print '<div class="wrap"><h2>Posts Log Table <a href="' . $admin_settings_page_url . '" class="font-size12">Back to settings page</a></h2>';
// Prepare table
$table->prepare_items();
// Display table
$table->display();
print '</div>';
