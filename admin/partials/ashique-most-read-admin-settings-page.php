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
            die('Set at least 1 to post number');
        }
        elseif ($_POST['ashique_most_read_post_days_number'] < 1) {
            die('Set at least 1 to days number');
        }
        else {
            update_option( 'most_read_post_number', $_POST['ashique_most_read_post_post_number'] );
            update_option( 'most_read_days_number', $_POST['ashique_most_read_post_days_number'] );
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
                    <th scope="row"><label for="ashique-most-read-post-post-number">How many posts you want to show: </label></th>
                    <td>
                        <input type="number" id="ashique-most-read-post-post-number" name="ashique_most_read_post_post_number" class="regular-text" value="<?php echo get_option( 'most_read_post_number', 4 );?>">
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <th scope="row"><label for="ashique-most-read-post-days-number">How many days interval you want to see: </label></th>
                    <td>
                        <input type="number" id="ashique-most-read-post-days-number" name="ashique_most_read_post_days_number" class="regular-text" value="<?php echo get_option( 'most_read_days_number', 7 );?>">
                    </td>
                </tr>
            </tbody>
        </table>

        <?php submit_button();?>
    </form>
</div>