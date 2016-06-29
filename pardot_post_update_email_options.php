<?php
    add_action('admin_menu', 'pardot_post_update_email_settings');

    function pardot_post_update_email_settings() {

        add_options_page('Pardot Post Updates','Pardot Post Update Email Settings','manage_options','pardot_post_update_email','pardot_post_update_email_settings_page');
        add_action( 'admin_init', 'register_pardot_post_update_email_settings' );
    }


    function register_pardot_post_update_email_settings() {
        register_setting( 'pardot_post_update_email_settings_group', 'pardot_post_update_email_account_email' );
        register_setting( 'pardot_post_update_email_settings_group', 'pardot_post_update_email_account_password' );
        register_setting( 'pardot_post_update_email_settings_group', 'pardot_post_update_email_account_user_key' );
        register_setting( 'pardot_post_update_email_settings_group', 'pardot_post_update_email_replyto_email' );
        register_setting( 'pardot_post_update_email_settings_group', 'pardot_post_update_email_subject_prefix' );
        register_setting( 'pardot_post_update_email_settings_group', 'pardot_post_update_email_campaign_id' );
        register_setting( 'pardot_post_update_email_settings_group', 'pardot_post_update_email_list_ids' );
        register_setting( 'pardot_post_update_email_settings_group', 'pardot_post_update_email_template_id' );
        register_setting( 'pardot_post_update_email_settings_group', 'pardot_post_update_email_tags' );
        register_setting( 'pardot_post_update_email_settings_group', 'pardot_post_update_email_template' );
    }


    function pardot_post_update_email_settings_page() {
        ?>
        <div class="wrap">
        <h2>Pardot Post Updates</h2>

        <form method="post" action="options.php">
            <?php settings_fields( 'pardot_post_update_email_settings_group' ); ?>
            <?php do_settings_sections( 'pardot_post_update_email_settings_group' ); ?>
            <table class="form-table">
                <tr valign="top">
                <th scope="row">Pardot Account Email</th>
                <td><input type="text" name="pardot_post_update_email_account_email" value="<?php echo esc_attr( get_option('pardot_post_update_email_account_email') ); ?>" /></td>
                </tr>
                 
                <tr valign="top">
                <th scope="row">Pardot Account Password</th>
                <td><input type="password" name="pardot_post_update_email_account_password" value="<?php echo esc_attr( get_option('pardot_post_update_email_account_password') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row">Pardot Account User Key (you can find this on https://pi.pardot.com/account)</th>
                <td><input type="text" name="pardot_post_update_email_account_user_key" value="<?php echo esc_attr( get_option('pardot_post_update_email_account_user_key') ); ?>" /></td>
                </tr>

                <tr valign="top">
                <th scope="row">ReplyTo Email</th>
                <td><input type="text" name="pardot_post_update_email_replyto_email" value="<?php echo esc_attr( get_option('pardot_post_update_email_replyto_email') ); ?>" /></td>
                </tr>
                 
                <tr valign="top">
                <th scope="row">Subject Prefix (optional, will come before the post title in the email subject) </th>
                <td><input type="text" name="pardot_post_update_email_subject_prefix" value="<?php echo esc_attr( get_option('pardot_post_update_email_subject_prefix') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row">Pardot Campaign Id</th>
                <td><input type="text" name="pardot_post_update_email_campaign_id" value="<?php echo esc_attr( get_option('pardot_post_update_email_campaign_id') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row">Pardot List Id</th>
                <td><input type="text" name="pardot_post_update_email_list_ids" value="<?php echo esc_attr( get_option('pardot_post_update_email_list_ids') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row">Pardot Template Id</th>
                <td><input type="text" name="pardot_post_update_email_template_id" value="<?php echo esc_attr( get_option('pardot_post_update_email_template_id') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row">Pardot Email Tags</th>
                <td><input type="text" name="pardot_post_update_email_tags" value="<?php echo esc_attr( get_option('pardot_post_update_email_tags') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row">Email Template</th>
                <td>
                    <?php
                        $templateEditorSettings = array(
                            'teeny' => true,
                            'textarea_rows' => 100,
                            'textarea_name' => 'pardot_post_update_email_template',
                            'tabindex' => 1
                        );
                        $defaultTemplate = file_get_contents("../wp-content/plugins/pardot_post_update_email/default-template.html");
                        wp_editor(esc_textarea( get_option('pardot_post_update_email_template', $defaultTemplate )), 'pardot-post-update-email-template-editor', $templateEditorSettings);
                    ?>
                </td>
                </tr>
            </table>

            <?php submit_button(); ?>

        </form>
        </div>
        <?php 
    } 
?>
