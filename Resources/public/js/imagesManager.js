imagesManager = function(options){
	this._initialize();

}

imagesManager.instance = null;

imagesManager.getInstance = function (){
	if(imagesManager.instance == null)
		imagesManager.instance = new imagesManager();
	return imagesManager.instance;
}

imagesManager.prototype = {
    _initialize: function(){
        
    },
    
    hoverImages: function(){
      $('.img_thumb_container').each(function(index, value) {
        $(this).hover(function(){
          $(this).find('div.img_edit').show();
          $(this).find('div.img_delete').show();
        },
        function(){
          $(this).find('div.img_edit').hide();
          $(this).find('div.img_delete').hide();
        });
      });
    },
    
    initializeAlbumUploaderBox: function()
    {
      $(".album_uploader_link").colorbox({iframe: true, width: "80%", height: "80%"});
    },
    
    initializeAlbumSortableBox: function()
    {
      $(".album_sortable_link").each(function(){
        var object = $(this);
        object.colorbox({
          iframe: true, 
          width: "80%", 
          height: "80%", 
          onClosed:function(){ 
            imagesManager.getInstance().refreshAlbums(object.attr("albumId"));
          }
        });
      });
    },
    
    refreshAlbums: function(albumId)
    {
      $.ajax({
          url: $("#refreshAlbumUrl").val(),
          data: {'id': albumId},
          type: 'post',
          dataType: 'json',
          success: function(json){
              if(json.status == "OK")
              {
                $("#album_" + albumId + " .img_thumb_ul").html(json.options.html);
                imagesManager.getInstance().hoverImages();
                imagesManager.getInstance().createColorboxMiniatureFrames();
              }
          }
      });

      return false; 
    },
    
    removeImage: function(mUrl, confirmationText, itemId)
    {
      if(confirm(confirmationText))
      {
        $.ajax({
          url: mUrl,
          type: 'post',
          dataType: 'json',
          success: function(json)
          {
            if(json.status == "OK")
            {
              $('#file_container_' + itemId).fadeOut(500, function() {$(this).remove();});
            }
          }        
        });
      }
      
    },
    
    createColorboxMiniatureFrames: function()
    {
      //$.colorbox.remove();
      $('.img_edit a').colorbox();
    },
    
    saveFileEditForm: function(form)
    {
      $("#saving_image_admin_file").show();
      $(".admin_file_action_button").hide();
      $.ajax({
          url: $(form).attr('action'),
          data: $(form).serialize(),
          type: 'post',
          dataType: 'json',
          success: function(json){
              if(json.result == "false" || json.result == false)
              {
                $("#form_errors_admin_file").html(json.errors);
                console.log(json.errors);
              }
              else
              {
                $("#form_errors_admin_file").html(" ");
              }
          }
          , 
          complete: function()
          {
            $.colorbox.resize();
            $("#saving_image_admin_file").hide();
            $(".admin_file_action_button").show();
          }
      });
      return false; 
    }

}

$( document ).ready(function() {
    imagesManager.getInstance().hoverImages();
    imagesManager.getInstance().initializeAlbumUploaderBox();
    imagesManager.getInstance().initializeAlbumSortableBox();
    imagesManager.getInstance().createColorboxMiniatureFrames();
});