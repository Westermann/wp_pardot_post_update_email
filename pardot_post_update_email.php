<?php 
    /*
    Plugin Name: Pardot Post Update Email 
    Plugin URI: www.zappistore.com
    Description: This plugin sends an email to a pardot list using a selected pardot template when a new post is set form pending to published.
    Author: Jonas Paul Westermann
    Version: 1.0
    Author URI: www.github.com/westermann
    */

    require_once('pardot_post_update_email_options.php');
    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_pardot_post_update_email_action_links' );
    function add_pardot_post_update_email_action_links ( $links ) {
     $mylinks = array(
     '<a href="' . admin_url( 'options-general.php?page=pardot_post_update_email' ) . '">Settings</a>',
     );
    return array_merge( $links, $mylinks );
    }

    include 'pardot_post_update_email_notifications.php';

    function callPardotApi($url, $data, $method) {
        $queryString = http_build_query($data, null, '&');
        $curl_handle = curl_init($url);

        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl_handle, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);

        if (strcasecmp($method, 'POST') === 1) {
          curl_setopt($curl_handle, CURLOPT_POST, true);
        } elseif (strcasecmp($method, 'GET') !== 0) {
          curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        }

        $pardotApiResponse = curl_exec($curl_handle);
        if ($pardotApiResponse === false) {
            $humanReadableError = curl_error($curl_handle);
            $httpResponseCode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
            curl_close($curl_handle);
            throw new Exception("Unable to successfully complete Pardot API call to $url -- curl error: \"$humanReadableError\", HTTP response code was: $httpResponseCode");
        }

        curl_close($curl_handle);

        return $pardotApiResponse;
    }

    function getPreviewText( $fullText ) {
        preg_match('#^<p>(.*?)</p>#i', $fullText, $matches);
        $firstParagraph = $matches[0];
        if (strlen($firstParagraph) > 256) {
            return $firstParagraph;
        } else {
            return substr($fullText,0,255);
        }
    }

    function sendPardotEmail( $post ) {
        $authorId       = $post->post_author;
        $authorName     = get_the_author_meta( 'display_name', $author );
        $euthorEmail    = get_the_author_meta( 'user_email', $author );
        $postTitle      = $post->post_title;
        $postLink       = get_permalink( $post );
        $imageLink      = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' )[0];
        $content        = apply_filters ("the_content", $post->post_content);

        $replyToEmail           = get_option('pardot_post_update_email_replyto_email','No replyto email set.');
        $subjectPrefix          = get_option('pardot_post_update_email_subject_prefix','No subject prefix set.');
        $pardotEmailTemplateId  = get_option('pardot_post_update_email_template_id','No template id set.');
        $pardotCampaignId       = get_option('pardot_post_update_email_campaign_id','No campaign id set.');
        $pardotListIds          = get_option('pardot_post_update_email_list_ids','No list ids set.');
        $pardotTags             = get_option('pardot_post_update_email_tags','No tags set.');
        $pardotUserKey          = get_option('pardot_post_update_email_account_user_key','No user key set.');
        $pardotEmail            = get_option('pardot_post_update_email_account_email','No account email set.');
        $pardotPassword         = get_option('pardot_post_update_email_account_password','No account email set.');

        $contentPreview = getPreviewText($content);

        $template =  file_get_contents("../wp-content/plugins/pardot_post_update_email/template.html");
        if (!$template) { throw new Exception("There was no template file found. Please place a template.html in wp-content/plugins/pardot_post_update_email/"); }
        $templateVariables = array(
            '%%post_title%%',
            '%%post_content%%',
            '%%post_link%%',
            '%%post_image_link%%'
        );
        $inputs = array(
            $postTitle,
            $contentPreview,
            $postLink,
            $imageLink
        );
        $htmlTemplate = str_replace($templateVariables, $inputs, $template);

        $authRes = callPardotApi(
            'https://pi.pardot.com/api/login/version/3',
            array(
                'user_key' => $pardotUserKey,
                'email' => $pardotEmail,
                'password' => $pardotPassword,
                'format' => 'json'
            ),
            'POST'
        );
        $pardotApiKey = json_decode($authRes, true)[api_key];
        
        $emailSendRes = callPardotApi(
            'https://pi.pardot.com/api/email/version/3/do/send',
            array(
                'user_key' => $pardotUserKey,
                'api_key' => $pardotApiKey,
                'campaign_id' => $pardotCampaignId,
                'from_name' => $authorName,
                'from_email' => $authorEmail,
                'replyto_email' => $replyToEmail,
                'html_content' => $htmlTemplate,
                'list_ids' => $pardotListIds,
                'tags' => $pardotTags,
                'name' => $subjectPrefix . ' ' . $postTitle,
                'subject' => $postTitle,
                'email_template_id' => $pardotEmailTemplateId,
                'format' => 'json' 
            ),
            'POST'
        );
        notify_success(json_decode($emailSendRes,true));
    }

    add_action( 'pending_to_publish', 'sendPardotEmail', 10, 2 );
?>
