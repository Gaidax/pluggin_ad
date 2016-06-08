jQuery(function($){
$(document).ready(function(){
    // $('#insert-my-media').click(open_media_window);
    if($('#images_id').val() != '' && $('#images_url').val() != ''){
        $('#open_media').text("Edit Slider");
    }
    $('#open_media').click(function(e){
        e.preventDefault();
        var target = $('#images_id');
        var target_url = $('#images_url');
        var btnSave = $('#publishing-action input.button');

        if(target.val() == '' && target_url.val() == ''){

            var wpmedia = wp.media({
                title: 'Insert a media',
                library: {type:'image'},
                multiple: true,
                button: {text:'Insert'}
            });

            wpmedia.on('select', function(){
                var ids = [];
                var urls = [];
                var models =  wpmedia.state().get('selection').toArray();
                for (var i = 0; i < models.length; i++) {
                    var file = models[i].toJSON();
                    ids.push(file.id);
                    urls.push(file.url);
                };
                target.val(ids.join(","));
                target_url.val(urls.join(","));
                $('#deleting_slider').val("");
                $('#open_media').text("Adding...");
                btnSave.click();
            });
            wpmedia.open();
        }else{
            wp.media.gallery
            .edit('[gallery ids="'+ target.val() +'" urls="'+ target_url.val() +'"]')
            .on('update', function(g){
                var ids = [];
                var urls = [];
                for (var i = 0; i < g.models.length; i++) {
                    var file = g.models[i].toJSON();
                    ids.push(file.id);
                    urls.push(file.url);
                };
                target.val(ids.join(","));
                target_url.val(urls.join(","));
                $('#deleting_slider').val("");
                $('#open_media').text("Editing...");
                btnSave.click();
            });
        }

    });

    $('#save_desc').click(function(e){
        e.preventDefault();
        var target = $('#desc_editor');
        var btnSave = $('#publishing-action input.button');         
                target.val(target.val());
                btnSave.click();
    });

    $('#delete_slider').click(function(e){
        e.preventDefault();
        /*var target = $('#images_id');
        var target_url = $('#images_url');*/
        var btnSave = $('#publishing-action input.button'); 
            /*target.val("");
            target_url.val("");*/
            $('#deleting_slider').val("Deleting...");
            $('#delete_slider').text("Deleting...");
            btnSave.click();
    });

    });

});