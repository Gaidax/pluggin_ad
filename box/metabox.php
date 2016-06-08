<?php


function koplan_add_metabox(){
add_meta_box(
        'koplan_metabox_gallery',
        'Games Thumbnails',
        'koplan_show_metabox',
        'post'
);
}
function koplan_add_maps_metabox(){
    add_meta_box(
            'koplan_metabox_maps',
        'Description',
        'koplan_show_maps_metabox',
        'post'
);
}
function koplan_show_metabox($post){
$ids = get_post_meta($post->ID, 'gallery_images', true);
$urls = get_post_meta($post->ID,'images',true); 
?>
<a href="#" id="open_media" class="button">Add Slider</a>
<hr>
<input type="hidden" name="gallery_images" id="images_id" value="<?php echo $ids; ?>">
<input type="hidden" name="gallery_urls" id="images_url" value="<?php echo $urls; ?>">
<input type="hidden" name="deleting_slider_post_meta" id="deleting_slider" value="<?php echo $urls; ?>">
<?php

    if($ids=="" and  $urls==""){
       return;
    }
    else{
        echo do_shortcode('[gallery ids="'.$ids.'" urls="'.$urls.'"]');
    }
?>
<hr>
<a href="#" id="delete_slider" name="delete_slider_post_meta" class="button">Delete Slider</a>
<?php
}

function koplan_save_gallery_metabox($post_id){
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
}

    if(! isset($_POST['gallery_images']) && !isset($_POST['gallery_urls'])){
        return;
    }

    $ids = sanitize_text_field( $_POST['gallery_images'] );
    $urls = sanitize_text_field( $_POST['gallery_urls'] );

    $terms = wp_get_object_terms( $post_id, 'category', array( 'fields' => 'names' ) );
        /*$termsname = $terms[0]->name;*/
        if(strlen($terms[1]) > strlen($terms[0])){
            $term = $terms[1];
        }
        else{
            $term = $terms[0];  
        }

    $sldata = '<slider images="'.$term.'" />';
    update_post_meta($post_id, 'slider', $sldata);
    update_post_meta($post_id, 'gallery_images', $ids);
    update_post_meta($post_id, 'images', $urls);

    if(isset($_POST['deleting_slider_post_meta']) && $_POST['deleting_slider_post_meta'] != ""){
        delete_post_meta($post_id, 'slider', $sldata);
        delete_post_meta($post_id, 'gallery_images', $ids);
        delete_post_meta($post_id, 'images', $urls);
    }
    }

function koplan_show_maps_metabox($post){
    $desc = get_post_meta($post->ID,'mapsdesc',true);
    if($desc!=""){
?>
    <textarea name="maps_descriptions" id="desc_editor" placeholder="Insert Descriptions Here" class="wp-editor-area" cols="40" autocomplete="off" style="height:320px; width:100%;"><?php echo $desc; ?></textarea>
<?php
}else{
?>
<textarea name="maps_descriptions" id="desc_editor" placeholder="Insert Descriptions Here" class="wp-editor-area" cols="40" autocomplete="off" style="height:320px; width:100%;"></textarea>
<?php
}
?>  
<hr>
<a href="#" class="button" id="save_desc">Save</a>
<?php
}

function koplan_save_maps_desc_metabox($post_id){
if (define('DOING_AUTOSAVE') && DOING_AUTOSAVE){
    return;
}
if(!isset($_POST['maps_descriptions'])){
    return;
}
$desc = $_POST['maps_descriptions'];
update_post_meta($post_id,'mapsdesc',$desc);
}

add_action( 'add_meta_boxes', 'koplan_add_metabox' );
add_action('add_meta_boxes','koplan_add_maps_metabox');
add_action( 'save_post', 'koplan_save_gallery_metabox' );
add_action( 'save_post', 'koplan_save_maps_desc_metabox' );

?>