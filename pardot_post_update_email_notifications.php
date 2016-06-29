<?php
    add_action('admin_head-post.php', 'add_plugin_notice');

    function add_plugin_notice() {
      if (get_option('display_my_admin_message')) { 
        add_action('admin_notices' , create_function( '', "echo '" . get_option('my_admin_message') . "';" ) );
        update_option('display_my_admin_message', 0); 
      }
    }

    function notify_success( $resJson ) {
        $resStatus = $resJson['@attributes'][stat];
        if ($resStatus == "ok"){
            update_option('my_admin_message', '<div class="notice notice-success"> <p>An email was sent out to the pardot list(s)!</p></div>');
        } else if ($resStatus == "fail") {
            update_option('my_admin_message', '<div class="notice notice-error"><p>There was a problem sending out a blog update email: ' . $resJson['err'] . '</p></div>');
        } else {
            update_option('my_admin_message', '<div class="notice notice-error"><p>There was a problem sending out a blog update email!</p></div>');
        }
        update_option('display_my_admin_message', 1);
    }
?>
