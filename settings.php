<?php
// create custom plugin settings menu
add_action('admin_menu', 'mps_store_create_menu');

function mps_store_create_menu()
{

    //create new top-level menu
    add_options_page('MPS Store Locator Settings', 'Store Locator', 'administrator', __FILE__, 'mps_store_settings_page');

    //call register settings function
    add_action('admin_init', 'register_mps_store_settings');
}


function register_mps_store_settings()
{
    //register our settings
    register_setting('register_mps_store_google_api_group', 'mps_store_google_api');
}

function mps_store_settings_page()
{
?>
    <div class="wrap">
        <h1>MPS Store Locator</h1>

        <form method="post" action="options.php">
            <?php settings_fields('register_mps_store_google_api_group'); ?>
            <?php do_settings_sections('register_mps_store_google_api_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Google API Key</th>
                    <td><input type="text" name="mps_store_google_api" style="width: 400px;" value="<?php echo esc_attr(get_option('mps_store_google_api')); ?>" /></td>
                </tr>
            </table>

            <?php submit_button(); ?>

        </form>
    </div>
<?php } ?>