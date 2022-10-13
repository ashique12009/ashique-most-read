<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://ashique12009.blogspot.com/
 * @since      1.0.0
 *
 * @package    Ashique_Most_Read
 * @subpackage Ashique_Most_Read/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ashique_Most_Read
 * @subpackage Ashique_Most_Read/public
 * @author     khandoker Ashique Mahamud <ashique12009@gmail.com>
 */
class Ashique_Most_Read_Public {

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
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ashique_Most_Read_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ashique_Most_Read_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, ASHIQUE_MOST_READ_PLUGIN_URL . 'public/css/ashique-most-read-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ashique_Most_Read_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ashique_Most_Read_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, ASHIQUE_MOST_READ_PLUGIN_URL . 'public/js/ashique-most-read-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Store post counter during user visits in post single page
	 */
	public function ashique_most_read_track_post_views($post_id) {
		if (!is_single()) {
			return;
		}
		if (empty($post_id)) {
			global $post;
			$post_id = $post->ID;
		}

		$this->ashique_most_read_set_post_views($post_id);
	}

	/**
	 * Store read counter into DB table
	 * @param int $post_id
	 * @return void
	 */
	public function ashique_most_read_set_post_views($post_id) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'ashique_most_read_posts';

		$today_date = date('Y-m-d', time());

		// Get last read counter of today's date
		$result = $wpdb->get_results("SELECT * FROM $table_name WHERE read_date='$today_date' AND post_id=" . $post_id, OBJECT);

		if (count($result) > 0) { // Update counter as today date found
			$data = [
				'read_counter' => $result[0]->read_counter+1
			];
			$where = [
				'read_date' => $today_date,
				'post_id' => $post_id
			];
			
			$wpdb->update($table_name, $data, $where);
		}
		else { // Insert as new entry for this date and for this post
			$data = [
				'post_id' => $post_id,
				'read_counter' => 1,
				'read_date' => date('Y-m-d', time())
			];

			$wpdb->insert($table_name, $data);
		}
	}

	/**
	 * Adding shortcode
	 */
	public function ashique_most_read_add_shortcode_for_posts() {
		add_shortcode('show_most_read_posts', [$this, 'ashique_make_query_and_show_posts']);
	}

	/**
	 * Shortcode output
	 */
	public function ashique_make_query_and_show_posts() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'ashique_most_read_posts';

		// How many posts to show
		$most_read_post_number = (int) get_option( 'most_read_post_number', 4 );
		$most_read_days_number = (int) get_option( 'most_read_days_number', 7 );

		$table_sql = "SELECT post_id, MAX(read_date) as read_date, SUM(read_counter) as read_counter  
				FROM $table_name 
				WHERE 
				read_date >= DATE(NOW() - INTERVAL %d DAY)  
				GROUP BY post_id 
				ORDER BY read_counter DESC 
				LIMIT %d";

		$sql = $wpdb->prepare($table_sql, $most_read_days_number, $most_read_post_number);

		$results = $wpdb->get_results($sql);

		$html = "<div class='ashique-most-read-posts-list-wrapper'>";
		if ($results) {
			foreach ($results as $result) {
				$title = get_the_title( $result->post_id );
				$post_link = get_the_permalink( $result->post_id );
				$post_content = $this->ashique_most_read_get_excerpt( 80, get_the_content( $result->post_id ) );
				$post_date = get_the_date( 'F d, Y', $result->post_id );
				$default_image_url = ASHIQUE_MOST_READ_PLUGIN_URL . 'public/images/default-image.png';
				$post_thumbnail_url = get_the_post_thumbnail_url( $result->post_id, 'medium' ) ? get_the_post_thumbnail_url( $result->post_id, 'medium' ) : $default_image_url;

				$html .= '<div class="ashique-custom-card sub-article">';
                $html .= '<div class="ahique-card-feaured-image-container card-image card-feature-img trending-posts-img">';
                $html .= '<a class="ashique-image-anchor" href="' . $post_link . '">';
                $html .= '<img src="' . $post_thumbnail_url . '" alt="article image" class="ashique-img img-fluid wp-post-image">';
                $html .= '</a>';
                $html .= '</div>';
                $html .= '<div class="ashique-card-content card-content">';
                $html .= '<a class="anchor" href="' . $post_link . '">';
                $html .= '<h3 class="post-title fw-bold mb-2">' . $title . '</h3>';
            	$html .= '</a>';
                $html .= '<div class="date-block">' . $post_date . '</div>';
                $html .= '<p class="sub-article-length">' . $post_content . '</p>';
                $html .= '<a class="ashique-read-more" href="' . $post_link . '">Read More</a>';
                $html .= '</div>';
                $html .= '</div>'; 
			}
		}
		$html .= "</div>";

		return $html;
	}

	/**
	 * Set results in transient that develoeper can get result by get_transient in any template
	 */
	public function ashique_most_read_set_results_in_transient() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'ashique_most_read_posts';

		// How many posts to show
		$most_read_post_number = get_option( 'most_read_post_number', 4 );
		$most_read_days_number = get_option( 'most_read_days_number', 7 );

		$table_sql = "SELECT post_id, MAX(read_date) as read_date, SUM(read_counter) as read_counter  
				FROM $table_name 
				WHERE 
				read_date >= DATE(NOW() - INTERVAL %d DAY)  
				GROUP BY post_id 
				ORDER BY read_counter DESC 
				LIMIT %d";

		$sql = $wpdb->prepare($table_sql, $most_read_days_number, $most_read_post_number);

		$results = $wpdb->get_results($sql);

		set_transient('most_read_posts', $results);
	}
	
	/**
	 * Get excerpt
	 */
	public function ashique_most_read_get_excerpt($limit, $source = null) {
		$excerpt = $source == "content" ? get_the_content() : get_the_excerpt();
		$excerpt = preg_replace(" (\[.*?\])", '', $excerpt);
		$excerpt = strip_shortcodes($excerpt);
		$excerpt = strip_tags($excerpt);
		$excerpt = substr($excerpt, 0, $limit);
		$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
		$excerpt = trim(preg_replace('/\s+/', ' ', $excerpt)) . '...';
		return $excerpt;
  	}

}
