<?php

function migaSocialPhotoFeedCronJobs($schedules)
{
    $schedules['monthly'] = array(
        'display' => __('Once monthly', 'migaSocialPhotoFeed_feed'),
        'interval' => 2635200,
    );
    return $schedules;
}
add_filter('cron_schedules', 'migaSocialPhotoFeedCronJobs');

if (! wp_next_scheduled('migaSocialPhotoFeed_read_feed')) {
    wp_schedule_event(time(), 'hourly', 'migaSocialPhotoFeed_read_feed');
}
if (! wp_next_scheduled('migaSocialPhotoFeed_read_refresh_token')) {
    wp_schedule_event(time(), 'monthly', 'migaSocialPhotoFeed_read_refresh_token');
}
