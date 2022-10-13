<?php 
if (!defined('ABSPATH')) {
    die;
}
?>

<?php 
    if (isset($_POST['ashique_most_read_post_post_number'])) {
        if (!wp_verify_nonce($_POST['most_read_post_number_settings_nonce_field'], 'most-read-post-number-settings-nonce' )) {
            die('Something went wrong!');
        }
        elseif ($_POST['ashique_most_read_post_post_number'] < 1) {
            header('Location: ' . admin_url( 'options-general.php?page=most-read-posts-settings&error=1' ));
        }
        elseif ($_POST['ashique_most_read_post_days_number'] < 1) {
            header('Location: ' . admin_url( 'options-general.php?page=most-read-posts-settings&error=2' ));
        }
        else {
            update_option( 'most_read_post_number', sanitize_text_field( $_POST['ashique_most_read_post_post_number'] ) );
            update_option( 'most_read_days_number', sanitize_text_field( $_POST['ashique_most_read_post_days_number'] ) );
            header('Location: ' . admin_url( 'options-general.php?page=most-read-posts-settings&error=3' ));
        }
    }

?>

<div class="wrap">
    <h1>Most read posts settings</h1>
    <form method="post" action="">
        <?php wp_nonce_field( 'most-read-post-number-settings-nonce', 'most_read_post_number_settings_nonce_field' ); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="ashique-most-read-post-post-number"><?php _e( 'How many posts you want to show', 'ashique-most-read' ); ?>: </label></th>
                    <td>
                        <input type="number" id="ashique-most-read-post-post-number" name="ashique_most_read_post_post_number" class="regular-text" value="<?php echo esc_html( get_option( 'most_read_post_number', 4 ) ) ;?>">
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <th scope="row"><label for="ashique-most-read-post-days-number"><?php _e( 'How many days interval you want to see', 'ashique-most-read' ); ?>: </label></th>
                    <td>
                        <input type="number" id="ashique-most-read-post-days-number" name="ashique_most_read_post_days_number" class="regular-text" value="<?php echo esc_html( get_option( 'most_read_days_number', 7 ) ) ;?>">
                    </td>
                </tr>
            </tbody>
        </table>

        <?php submit_button();?>
    </form>
</div>