<?php
/**
* Plugin Name
*
* @package           PluginPackage
* @author            Michael Gangolf
* @copyright         2022 Michael Gangolf
* @license           GPL-2.0-or-later
*
* @wordpress-plugin
* Plugin Name:       Social photo feed for Elementor
* Description:       Social photo feed for Elementor - downloads and display local Instagram images in a simple image list.
* Version:           1.2.1
* Requires at least: 5.2
* Requires PHP:      7.2
* Author:            Michael Gangolf
* Author URI:        https://www.migaweb.de/
* License:           GPL v2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

require_once("includes/instagram_tools.php");
require_once("includes/cron_jobs.php");

function migaSocialPhotoFeed_enqueueScripts()
{
    wp_register_style('migaSocialPhotoFeed_styles', plugins_url('styles/main.css', __FILE__));
    wp_enqueue_style('migaSocialPhotoFeed_styles');
}

use Elementor\Plugin;

add_action('init', static function () {
    if (! did_action('elementor/loaded')) {
        return false;
    }
    require_once(__DIR__ . '/widget/instagram_feed.php');
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor_Widget_migaSocialPhotoFeed_feed());
});


function migaSocialPhotoFeed_menu()
{
    add_menu_page(
        __('Social Photo Feed', 'migaSocialPhotoFeed_feed'),
        __('Social Photo Feed', 'migaSocialPhotoFeed_feed'),
        'edit_posts',
        'migaSocialPhotoFeed-menu-detailpage',
        'migaSocialPhotoFeed_menu_detailpage',
        'dashicons-images-alt'
    );
}

function migaSocialPhotoFeed_menu_detailpage()
{
    ?>
    <h1>Social Photo Feed admin page</h1>
    <form method="POST" action="<?php echo admin_url('admin-post.php'); ?>">
    <?php
    settings_fields('migaSocialPhotoFeed-admin-page');
    do_settings_sections('migaSocialPhotoFeed-admin-page'); ?>

    <input type="submit" name="submit" id="submit" class="button button-primary" value="Save changes"  />
    <input type="submit" name="submit" id="submit" class="button button-primary" value="refresh token"  />
    <input type="submit" name="submit" id="submit" class="button button-secondary delete" value="remove all images"  />
    </form>
    <?php

    $imgs = migaSocialPhotoFeedGetImages();
    echo "<br/>Amount of images in database: ". sizeOf($imgs);

    echo '<br/><h3>Last four images:</h3>';
    $imgs = array_slice($imgs, 0, 4);
    foreach ($imgs as $img) {
        echo wp_get_attachment_image($img->ID, array(150,150));
    }
}


function migaSocialPhotoFeed_admin_init()
{
    add_settings_section(
        'instagram_admin_page',
        __('How to get the token', 'migaSocialPhotoFeed_feed'),
        'migaSocialPhotoFeedCallback',
        'migaSocialPhotoFeed-admin-page'
    );

    add_settings_field(
        'migaSocialPhotoFeed_token_field',
        __('Token', 'migaSocialPhotoFeed_feed'),
        'migaSocialPhotoFeed_admin_fields',
        'migaSocialPhotoFeed-admin-page',
        'instagram_admin_page'
    );

    register_setting('migaSocialPhotoFeed-admin-page', 'migaSocialPhotoFeed_token_field');
}

function migaSocialPhotoFeed_admin_fields()
{
    $keepAmount = get_option('migaSocialPhotoFeed_keep_field');
    if (empty($keepAmount)) {
        $keepAmount = 10;
    }
    ?>
    <input type="hidden" name="action" value="add_migaSocialPhotoFeed">
    <input type="text" id="migaSocialPhotoFeed_token_field" name="migaSocialPhotoFeed_token_field" value="<?php echo esc_html(get_option('migaSocialPhotoFeed_token_field')); ?>">
    <br/>
    Keep: <input type="number" id="migaSocialPhotoFeed_keep_field" name="migaSocialPhotoFeed_keep_field" value="<?php echo esc_html($keepAmount); ?>" > images
    <?php
    if (isset($_GET["error"]) || !empty(get_option("wp_schedule_event"))) {
        if ($_GET["error"] == "token_error" || get_option("wp_schedule_event") == "token_error") {
            echo '<strong>This token is not valid.</strong>';
            update_option('migaSocialPhotoFeed_token_error', '');
        }
    }
}

function migaSocialPhotoFeedGetImages()
{
    $args = array(
        'post_mime_type' => 'image',
        'numberposts'    => -1,
        'post_type'      => 'attachment' ,
        'meta_query' => array(
            array(
                'key' => 'insta_image_type',
                'value' => 'instagram',
                'compare' => '=',
            )
        )
     );

    return get_children($args);
}

function migaSocialPhotoFeedCallback()
{
    ?>
  <ul style="list-style:inherit;margin-left: 20px;">
    <li>Go here <a href="https://developers.facebook.com/apps/" target="_blank">https://developers.facebook.com/apps/</a> & Create an App - Consumer.</li>
    <li>Find Instagram card and click on Set Up</li>
    <li>Go to Products -> Instagram -> Basic Display -> Create App</li>
    <li>Click on Add or Remove Instagram Testers</li>
    <li>Scroll down and find Instagram Testers</li>
    <li>Add your Instagram account as Tester</li>
    <li>Now go here <a href="https://www.instagram.com/accounts/manage_access/" target="_blank">https://www.instagram.com/accounts/manage_access/</a> to Accept the invitation.</li>
    <li>Go to Products -> Instagram -> Basic Display -> Click on Generate Token</li>
    <li>Copy that token and add it below.</li>
    <li>click "Save changes" to store the config and download new images.</li>
</ul>

<?php
}

add_action('admin_menu', 'migaSocialPhotoFeed_menu');
add_action('admin_post_add_migaSocialPhotoFeed', 'migaSocialPhotoFeed_submit');
add_action('admin_init', 'migaSocialPhotoFeed_admin_init');
add_action('wp_enqueue_scripts', 'migaSocialPhotoFeed_enqueueScripts');
add_action('migaSocialPhotoFeed_read_feed', 'migaSocialPhotoFeed');
add_action('migaSocialPhotoFeed_read_refresh_token', 'migaSocialPhotoFeed_refreshToken');

function migaSocialPhotoFeed_submit()
{
    if (null !== wp_unslash($_POST['migaSocialPhotoFeed_token_field'])) {
        $value = sanitize_text_field($_POST['migaSocialPhotoFeed_token_field']);
        update_option('migaSocialPhotoFeed_token_field', $value);
    }

    if (null !== wp_unslash($_POST['migaSocialPhotoFeed_keep_field'])) {
        $value = (int) sanitize_text_field($_POST['migaSocialPhotoFeed_keep_field']);
        update_option('migaSocialPhotoFeed_keep_field', $value);
    }

    if ($_POST["submit"] == "remove all images") {
        $attached_images = migaSocialPhotoFeedGetImages();
        foreach ($attached_images as $image) {
            wp_delete_attachment($image->ID);
        }
    } elseif ($_POST["submit"] == "refresh token") {
        $output = migaSocialPhotoFeed_refreshToken();
    } else {
        $output = migaSocialPhotoFeed();
    }

    status_header(200);
    $url = sanitize_text_field($_POST['_wp_http_referer']);
    wp_redirect($url.'&error='.$output);
}
