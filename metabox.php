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
		'wp_attach_folder',
		'Upload and attach folder',
		'wp_attach_dir',
		'page',
		'side'
		);

		add_meta_box(
		'wp_attach_ex_folder',
		'Attach existing folder',
		'wp_attach_ex_dir',
		'page',
		'side'
		);
}

function wp_attach_dir() {

	wp_nonce_field(plugin_basename(__FILE__), 'wp_attached_folder_nonce');

	$html = 'Name the folder for a game: <input type="text" id="folder" name="folder" maxlength="5"/>';
	$html .= '<input type="file" id="wp_upl_dir" name="wp_upl_dir[]" size="25" webkitdirectory directory  multiple/>';
	$html .= '<p class="description">';
	$html .= 'Upload your folder here.';
	$html .= '</p>';

	echo $html;
}

function wp_attach_ex_dir() {
	wp_nonce_field(plugin_basename(__FILE__), 'wp_attached_ex_folder_nonce');

	$base = plugin_dir_path(__FILE__).'uploaded_games';

	$html = 'Attach existing game to the page';
	$html .= '<br>';
	$html .= "Folders: ";
	$html .= "<br><br>";
	if( is_dir($base) ) {
		if($dir = opendir($base)) {
			while(($file = readdir($dir))!== false) {
				if(strncmp( $file, '.', strlen( '.' ) )) {
					$html .=  '<input type ="radio" name = "attach_dir" value = "'.$base."/".$file .'"/>' .$file . "<br>";
				}				
			}
			closedir($dir);					
		}
	}
	$html .= '<br>';
	$html .= '<p class="description">';
	$html .= 'Attach a folder to the page here.';
	$html .= '</p>';
	echo $html;


}


function verify_and_upload( $id ) {

	if(!wp_verify_nonce($_POST['wp_attached_folder_nonce'], plugin_basename(__FILE__))) {
		return $id;
	} 

	if(!wp_verify_nonce($_POST['wp_attached_ex_folder_nonce'], plugin_basename(__FILE__))) {
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
	attach_existing($id);  
}

function attach_existing($id) {
	$attached = $_POST['attach_dir'];
	$fold = get_folder($attached);
	$file_num = 0;
	$arr = array();

	if( is_dir($attached) ) {
		if($dir = opendir($attached)) {
			while (($file = readdir($dir))!== false) {
				if(strncmp( $file, '.', strlen( '.' ) )) {
				    add_post_meta($id, 'attached_ex_file' .$file_num, plugin_dir_url(__FILE__) . $fold . $file);
                    update_post_meta($id, 'attached_ex_file' .$file_num, plugin_dir_url(__FILE__). $fold . $file);
                    $arr[] = $file;
                    $file_num++;
                }
			}
			closedir($dir);		
			add_post_meta($id, 'files_atached_existing', $file_num);
			update_post_meta($id, 'files_atached_existing', $file_num);

			add_post_meta($id, 'at', $arr);
			update_post_meta($id, 'at', $arr);
		}
	}
}

function get_folder($path) {
	$folder = explode('/', $path);
	return $folder[7] .'/'. $folder[8] .'/';
}


function upload_file_meta( $id ) {

	$file = $_FILES['wp_upl_dir'];
	$upload = array();

	if( !empty( $file ) ) {
        //if(in_array($uploaded_type, $supported_types))  //CHECK TYPES? OR JUST UPLOAD EVERYTHING?
			for($i=0; $i < count($file['name']); $i++) 
			{       	
				$upload[] = wp_upload_bits($file['name'][$i], null, file_get_contents($file['tmp_name'][$i]));     	
			}

		$file_num = 0;

		foreach ($upload as $uploaded)
		{
			if( isset($uploaded['error']) && $uploaded['error'] != 0 ) {
				wp_die('There was an error uploading your files. The error is: ' . $uploaded['error']); 
			}
                    add_post_meta($id, 'attached_file'.$file_num, $uploaded['url']);
                    update_post_meta($id, 'attached_file'.$file_num, $uploaded['url']);
                    $file_num++;
                }
                add_post_meta($id, 'files_uploaded', $file_num);
                update_post_meta($id, 'files_uploaded', $file_num);
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


        function find_name() {


        	$input = sanitize_text_field($_POST['folder']);
        	return $input;

        }

        function update_edit_form() {
        	echo ' enctype="multipart/form-data"';
        }

        ?>