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


        //if(in_array($uploaded_type, $supported_types)) {
        $file_arr = $_FILES['wp_attached_file'];

        foreach ($file_arr['name'] as $key => $val) {

                if ($file_arr['name'][$key]) { 
                    $file = array( 
                        'name' => $file_arr['name'][$key],
                        'type' => $file_arr['type'][$key], 
                        'tmp_name' => $file_arr['tmp_name'][$key], 
                        'error' => $file_arr['error'][$key],
                        'size' => $file_arr['size'][$key]
                    ); 
                    $_FILES = array ('wp_attached_file' => $file); 
                    foreach ($_FILES as $file => $array) {              
                        $newupload = upload_files($file, $id); 
                    }

        }
                    
        }
    }
}

        function upload_files($file, $id) {

                    $upload = wp_upload_bits($file['name'], null, file_get_contents($file['tmp_name']));
                    if(isset($upload['error']) && $upload['error'] != 0) {
                        wp_die('There was an error uploading your files. The error is: ' . $upload['error']);
                    } else {
                        add_post_meta($id, 'wp_attached_file', $upload);
                        update_post_meta($id, 'wp_attached_file', $upload); 
                    }

        }


                foreach ($file_arr['name'] as $key => $val) {

            


            $upload = wp_upload_bits($file_arr['name'][$key], null, file_get_contents($file_arr['tmp_name'][$key]));
                    if(isset($upload['error']) && $upload['error'] != 0) {
                        wp_die('There was an error uploading your files. The error is: ' . $upload['error']);
                    } else {
                        add_post_meta($id, 'wp_attached_file', $upload);
                        update_post_meta($id, 'wp_attached_file', $upload); 
                    }
        }