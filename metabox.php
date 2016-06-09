<?php

/**
 * @package Prototype
 * @version 0.01
 */


add_action('add_meta_boxes', 'upload_metabox');
add_action('save_post', 'save_file_meta_data');
add_action('post_edit_form_tag', 'update_edit_form');

function upload_metabox() {

    add_meta_box(
        'wp_attached_file',
        'Attached File',
        'wp_attached_file',
        'page',
        'side'
    );
}

function wp_attached_file() {
 
    wp_nonce_field(plugin_basename(__FILE__), 'wp_attached_file_nonce');
     
    $html = '<p class="description">';
    $html .= 'Upload your folder here.';
    $html .= '</p>';
    //$html .= '<input type="file" id="wp_attached_file" name="wp_attached_file" value="" size="25"/>';
    $html .= '<input type="file" id="wp_attached_file" name="wp_attached_file[]" size="25" webkitdirectory directory  multiple/>';
    echo $html;
}


function save_file_meta_data($id) {


    if(!wp_verify_nonce($_POST['wp_attached_file_nonce'], plugin_basename(__FILE__))) {
      return $id;
    } 
       
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return $id;
    } 
       
    if('page' == $_POST['post_type']) {
      if(!current_user_can('edit_page', $id)) {
        return $id;
      } 
    } else {
        if(!current_user_can('edit_page', $id)) {
            return $id;
        }
    }
    //$files = $_FILES['name'];

    $file_arr = $_FILES;
    $upload = array();
    if(!empty($file_arr)) {
        //if(in_array($uploaded_type, $supported_types))
        foreach ($file_arr as $file) {
        	for($i=0; $i < count($file['name']); $i++) {
        	
        	$upload[] = wp_upload_bits($file['name'][$i], null, file_get_contents($file['tmp_name'][$i]));
        	
        	}
     		
        }
        $j = 1;
         foreach ($upload as $uploaded) {
         	        if(isset($uploaded['error']) && $uploaded['error'] != 0) {
                    wp_die('There was an error uploading your files. The error is: ' . $uploaded['error']); 
                }
                    add_post_meta($id, 'wp_attached_file'.$j, $uploaded);      //YO DAWG ADD ANY $KEY YOU WANT, RETRIEVE IT ON FRONTEND
                    update_post_meta($id, 'wp_attached_file'.$j, $uploaded);
                    $j++;
                }
                add_post_meta($id, 'files_num', $j);
		 }                                                                                              

}




			       

function update_edit_form() {
    echo ' enctype="multipart/form-data"';
}

?>