<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://ashique12009.blogspot.com/
 * @since      1.0.0
 *
 * @package    Ashique_Most_Read
 * @subpackage Ashique_Most_Read/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ashique_Most_Read
 * @subpackage Ashique_Most_Read/includes
 * @author     khandoker Ashique Mahamud <ashique12009@gmail.com>
 */
class Ashique_Most_Read_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'ashique_most_read_posts';

		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) { // If table not exist, then create the table
			$table_sql = "CREATE TABLE `$table_name` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`post_id` int(11) NOT NULL,
				`read_counter` int(11) NOT NULL,
				`read_date` date NOT NULL,
				PRIMARY KEY (`id`)
			   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
	
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta($table_sql);
		}
	}

}
