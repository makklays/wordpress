<?php

/*
Plugin Name: MPlugin
Description: dessscriptttttion
Version: 001
Author: makklays
 */

require_once plugin_dir_path(__FILE__) . 'includes/mpl-function.php';

// menu верхнего уровня
add_action('admin_menu', 'my_admin_menu');

function my_admin_menu()
{
    add_menu_page(
        'страница плагина',
        'страница плагина',
        'manage_options',
        __FILE__,
        'function_mpl'
    );

    add_menu_page(
        'страница2 плагина',
        'страница2 плагина',
        'manage_options',
        __FILE__ . 'gjhg',
        'function_mpl2'
    );
}

global $jal_db_version;
$jal_db_version = "1.0";

register_activation_hook(__FILE__,'jal_install');

function jal_install ()
{
    global $wpdb;
    global $jal_db_version;

    $welcome_name = "Mr. Wordpress";
    $welcome_text = "Поздравляю, установка прошла успешно!";

    $table_name = $wpdb->prefix . "liveshoutbox";
    if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {

        $sql = "CREATE TABLE " . $table_name . " (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time bigint(11) DEFAULT '0' NOT NULL,
            name tinytext NOT NULL,
            text text NOT NULL,
            url VARCHAR(55) NOT NULL,
            UNIQUE KEY id (id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $rows_affected = $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $welcome_name, 'text' => $welcome_text ) );

        add_option("jal_db_version", $jal_db_version);

    }
}

function function_mpl(){
    // variables for the field and option names
    $opt_name = 'mt_favorite_food';
    $hidden_field_name = 'mt_submit_hidden';
    $data_field_name = 'mt_favorite_food';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );

        // Put an options updated message on the screen

        ?>
        <div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>
        <?php

    }
    // Now display the options editing screen
    echo '<div class="wrap">';
    // header
    echo "<h2>" . __( 'Menu Test Plugin Options', 'mt_trans_domain' ) . "</h2>";
    // options form
    ?>

    <form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
        <p><?php _e("Favorite Color:", 'mt_trans_domain' ); ?>
            <input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="20">
        </p><hr />
        <p class="submit">
            <input type="submit" name="Submit" value="<?php _e('Update Options', 'mt_trans_domain' ) ?>" />
        </p>
    </form>
    </div>
    <?php
}

function function_mpl2(){
    ?>
    <div class="wrap">
        <h2>Your Plugin Name</h2>

        <form method="post" action="options.php">
            <?php wp_nonce_field('update-options'); ?>

            <table class="form-table">

                <tr valign="top">
                    <th scope="row">New Option Name</th>
                    <td><input type="text" name="new_option_name" value="<?php echo get_option('new_option_name'); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Some Other Option</th>
                    <td><input type="text" name="some_other_option" value="<?php echo get_option('some_other_option'); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Options, Etc.</th>
                    <td><input type="text" name="option_etc" value="<?php echo get_option('option_etc'); ?>" /></td>
                </tr>

            </table>

            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="new_option_name,some_other_option,option_etc" />

            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>

        </form>
    </div>
    <?php
}



// create custom plugin settings menu
add_action('admin_menu', 'baw_create_menu');

function baw_create_menu() {

    //create new top-level menu
    add_menu_page('BAW Plugin Settings', 'BAW Settings', 'administrator', __FILE__ . 'newli', 'baw_settings_page',plugins_url('/images/icon.png', __FILE__));

    //call register settings function
    add_action( 'admin_init', 'register_mysettings' );
}

function register_mysettings() {
    //register our settings
    register_setting( 'baw-settings-group', 'new_option_name' );
    register_setting( 'baw-settings-group', 'some_other_option' );
    register_setting( 'baw-settings-group', 'option_etc' );
}

function baw_settings_page() {
    ?>
    <div class="wrap">
        <h2>Your Plugin Name</h2>

        <form method="post" action="options.php">
            <?php settings_fields( 'baw-settings-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">New Option Name</th>
                    <td><input type="text" name="new_option_name" value="<?php echo get_option('new_option_name'); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Some Other Option</th>
                    <td><input type="text" name="some_other_option" value="<?php echo get_option('some_other_option'); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Options, Etc.</th>
                    <td><input type="text" name="option_etc" value="<?php echo get_option('option_etc'); ?>" /></td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>

        </form>
    </div>
<?php } ?>
