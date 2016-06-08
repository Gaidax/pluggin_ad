<?php

/**
 * @package Prototype
 * @version 0.01
 */


add_action('add_meta_boxes', 'upload_metabox');
add_action('save_post', 'save_file_meta_data');
add_action('post_edit_form_tag', 'update_edit_form');

function upload_metabox() {
/*    add_meta_box(
        'wp_attached_file',
        'Attached File',
        'wp_attached_file',
        'post',
        'side'
    );*/

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
    $html .= '<input type="file" id="wp_attached_file" name="wp_attached_file" size="25" webkitdirectory directory multiple/>';
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
    if(!empty($_FILES['wp_attached_file']['name'])) {

        $supported_types = array('application/javascript');     

        $arr_file_type = wp_check_filetype(basename($_FILES['wp_attached_file']['name']));
        $uploaded_type = $arr_file_type['type'];


        //if(in_array($uploaded_type, $supported_types))
/*                $file_arr = $_FILES['wp_attached_file'];

        $upload = wp_upload_bits($file_arr['name'], null, file_get_contents($file_arr['tmp_name']));
                                                                                                             //WORKS, ADDS ONE FILE s
        if(isset($upload['error']) && $upload['error'] != 0) {
                    wp_die('There was an error uploading your files. The error is: ' . $upload['error']);
                }
                    add_post_meta($id, 'wp_attached_file', $upload);
                    update_post_meta($id, 'wp_attached_file', $upload);
*/


        $files_to_upload = array();
        foreach ($file_arr as $key => $value) {
                if($file_arr['name'][$key]) {
                    $file = array( 
                        'name' => $file_arr['name'][$key],
                        'type' => $file_arr['type'][$key], 
                        'tmp_name' => $file_arr['tmp_name'][$key], 
                        'error' => $file_arr['error'][$key],
                        'size' => $file_arr['size'][$key]
                    ); 

                  array_push($files_to_upload, $file);
                }
        }
        //$uploads = array();


                $upload = wp_upload_bits($file['name'], null, file_get_contents($file['tmp_name']));

                if(isset($upload['error']) && $upload['error'] != 0) {
                    wp_die('There was an error uploading your files. The error is: ' . $upload['error']);
                }
                    add_post_meta($id, 'wp_attached_file', $upload);
                    update_post_meta($id, 'wp_attached_file', $upload);  
                    

            }

            }
        


/*       $up_scripts = $_FILES['wp_attached_file'];
        $uploads = array();
        foreach ($up_scripts['name'] as $key => $script) {

            $upload = wp_upload_bits($script, null, file_get_contents($script['tmp_name']));
            array_push($uploads, $upload);
        }
        foreach ($uploads as $key => $upload) {
         if(isset($upload['error']) && $upload['error'] != 0) {
                wp_die('There was an error uploading your files. The error is: ' . $upload['error']);
            } else {
                
                 add_post_meta($key, 'wp_attached_file', $upload);
                 update_post_meta($key, 'wp_attached_file', $upload); 
                }
            }*/

        //} else {
           // wp_die("The file type that you've uploaded is not a script.");
        //}          

function update_edit_form() {
    echo ' enctype="multipart/form-data"';
}

?>