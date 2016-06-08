<?php

/**
 * @package Prototype
 * @version 0.01
 */
add_action('add_meta_boxes', 'upload_metabox');

function upload_metabox() {
    add_meta_box(
        'wp_attached_file',
        'Attached File',
        'wp_attached_file',
        'post',
        'side'
    );
     
    add_meta_box(
        'wp_attached_file',
        'Attached File',
        'wp_attached_file',
        'page',
        'side'
    );
}

?>