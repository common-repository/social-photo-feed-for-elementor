<?php

function migaSocialPhotoFeed_refreshToken()
{
    $token = get_option('migaSocialPhotoFeed_token_field');
    if ($token != "") {
        $ch = curl_init();
        $output = wp_remote_get("https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=".$token);
        $data = json_decode(wp_remote_retrieve_body($output));
        if (!json_last_error() && !empty($data->access_token)) {
            update_option('migaSocialPhotoFeed_token_field', $data->access_token);
        } else {
            if (stripos($output, "sorry") !== false) {
                return "token_error";
            }
        }
    }
}

function migaSocialPhotoFeedFileExists($filename)
{
    global $wpdb;
    return intval($wpdb->get_var("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%/$filename'"));
}

function migaSocialPhotoFeed_cleanFiles()
{
    $keepAmount = get_option('migaSocialPhotoFeed_keep_field');

    $args = array(
        'post_mime_type' => 'image',
        'numberposts'    => -1,
        'post_type'      => 'attachment' ,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => 'insta_image_type',
                'value' => 'instagram',
                'compare' => '=',
            )
        )
     );
    $attached_images = get_children($args);
    $i = 0;
    if (sizeOf($attached_images) > $keepAmount) {
        foreach ($attached_images as $obj) {
            if ($i >= $keepAmount) {
                wp_delete_attachment($obj->ID);
            }
            $i++;
        }
    }
}

function migaSocialPhotoFeed()
{
    $token = get_option('migaSocialPhotoFeed_token_field');
    if ($token != "") {
        $ch = curl_init();
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $fields = "media_url,media_type,thumbnail_url,permalink,timestamp,caption";
        $output = wp_remote_get("https://graph.instagram.com/me/media?fields=".$fields."&access_token=".$token);
        $data = json_decode(wp_remote_retrieve_body($output));
        if (!json_last_error() && empty($data->error)) {
            $list = $data->data;

            $options = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );

            $list = array_reverse(array_slice($list, 0, 10));

            foreach ($list as $key) {
                $mediaUrl = "";
                if ($key->media_type == "IMAGE") {
                    $mediaUrl = $key->media_url;
                } elseif ($key->media_type == "VIDEO") {
                    $mediaUrl = $key->thumbnail_url;
                }
                $data = parse_url($mediaUrl);
                $array = explode("/", $data['path']);
                $filename = $array[count($array) - 1];
                $uploaddir = wp_upload_dir();
                $uploadfile = $uploaddir['path'] . '/' . $filename;

                if (!file_exists($uploadfile) && !migaSocialPhotoFeedFileExists($filename)) {
                    $contents = @file_get_contents($mediaUrl, false, stream_context_create($options));
                    $savefile = fopen($uploadfile, 'w');
                    fwrite($savefile, $contents);
                    fclose($savefile);

                    $caption = $key->caption;
                    if (strlen($caption) > 100) {
                        $caption = substr($caption, 0, 100)."...";
                    }

                    $wp_filetype = wp_check_filetype(basename($filename), null);
                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title' => $filename,
                        'post_date' => $key->timestamp,
                        'post_content' => $caption,
                        'post_status' => 'inherit',
                    );

                    $attach_id = wp_insert_attachment($attachment, $uploadfile);

                    $imagenew = get_post($attach_id);
                    $fullsizepath = get_attached_file($imagenew->ID);
                    $attach_data = wp_generate_attachment_metadata($attach_id, $fullsizepath);
                    wp_update_attachment_metadata($attach_id, $attach_data);
                    update_post_meta($attach_id, '_wp_attachment_image_alt', get_bloginfo('name'));
                    update_post_meta($attach_id, 'insta_image_type', 'instagram');
                    update_post_meta($attach_id, 'insta_url', $key->permalink);
                    update_post_meta($attach_id, 'insta_type', $key->media_type);

                }
            }

            migaSocialPhotoFeed_cleanFiles();
        } elseif (stripos($output, "sorry") !== false) {
            update_option('migaSocialPhotoFeed_token_error', 'token_error');
            return "token_error";
        } else {
            update_option('migaSocialPhotoFeed_token_error', 'token_error');
            return "token_error";
        }
    }
}
