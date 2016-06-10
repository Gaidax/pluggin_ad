<?php

/**
 * @package Prototype
 * @version 0.01
 */

add_action('add_meta_boxes', 'upload_metabox');
add_action('save_post', 'verify_and_upload');
add_action('post_edit_form_tag', 'update_edit_form');
add_filter( 'upload_dir', 'upload_to_plugin_dir' );


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

	$html = 'Name the folder for a game: <input type="text" id="folder" name="folder" maxlength="5"/>';
	$html .= '<input type="file" id="wp_attached_file" name="wp_attached_file[]" size="25" webkitdirectory directory  multiple/>';
	$html .= '<p class="description">';
	$html .= 'Upload your folder here.';
	$html .= '</p>';

	echo $html;
}


function verify_and_upload( $id ) {

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

	upload_file_meta( $id );   
}

function find_name() {


	$input = sanitize_text_field($_POST['folder']);
	return $input;

}


function upload_file_meta( $id ) {

	$file_arr = $_FILES;
	$upload = array();
	if( !empty( $file_arr ) ) {
        //if(in_array($uploaded_type, $supported_types))  //CHECK TYPES? OR JUST UPLOAD EVERYTHING?
		foreach ($file_arr as $file)
		{
			for($i=0; $i < count($file['name']); $i++) 
			{       	
				$upload[] = wp_upload_bits($file['name'][$i], null, file_get_contents($file['tmp_name'][$i]));     	
			}

		}

		$file_num = 1;

		foreach ($upload as $uploaded)
		{
			if( isset($uploaded['error']) && $uploaded['error'] != 0 ) {
				wp_die('There was an error uploading your files. The error is: ' . $uploaded['error']); 
			}
                    add_post_meta($id, 'wp_attached_file'.$file_num, $uploaded);      //YO DAWG ADD ANY $KEY YOU WANT, RETRIEVE IT ON FRONTEND
                    update_post_meta($id, 'wp_attached_file'.$file_num, $uploaded);
                    $file_num++;
                }
                add_post_meta($id, 'files_num', $file_num);
            }                                                                                              

        }


        function upload_to_plugin_dir( $dir ) {

        	$custom_name = find_name();
        	$dir_n = "uploaded_games/".$custom_name;

        	$id = $_POST['post_id'];
        	$parent = get_post( $id )->post_parent;

        	if( "page" == get_post_type( $id ) || "page" == get_post_type( $parent ) ) {

        		$dir['path'] = plugin_dir_path(__FILE__) . $dir_n;
        		$dir['url']  = plugin_dir_url(__FILE__) . $dir_n;
        		$dir['basedir'] = plugin_dir_path(__FILE__) . $dir_n;
        		$dir['baseurl'] = plugin_dir_url(__FILE__) . $dir_n;
        	}
        	return $dir;
        }


        function update_edit_form() {
        	echo ' enctype="multipart/form-data"';
        }

        ?>