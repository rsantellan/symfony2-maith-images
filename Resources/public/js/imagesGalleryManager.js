imagesGalleryManager = function(options){
	this._initialize();

}

imagesGalleryManager.instance = null;

imagesGalleryManager.getInstance = function (){
	if(imagesGalleryManager.instance == null)
		imagesGalleryManager.instance = new imagesGalleryManager();
	return imagesGalleryManager.instance;
}

imagesGalleryManager.prototype = {
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
            imagesGalleryManager.getInstance().refreshAlbums(object.attr("albumId"));
          }
        });
      });
    },
    
    refreshAlbums: function(gallery)
    {
      $.ajax({
          url: $("#refreshAlbumUrl").val(),
          data: {'gallery': gallery},
          type: 'post',
          dataType: 'json',
          success: function(json){
              if(json.status == "OK")
              {
                $("#album_" + albumId + " .img_thumb_ul").html(json.options.html);
                imagesGalleryManager.getInstance().hoverImages();
              }
          }
      });

      return false; 
    },
    
    removeImage: function(mUrl, confirmationText, itemId, item)
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
              $(item).parent().parent().fadeOut(500, function() {$(this).remove();});
            }
          }        
        });
      }
      
    }

}

$( document ).ready(function() {
    imagesGalleryManager.getInstance().hoverImages();
    imagesGalleryManager.getInstance().initializeAlbumUploaderBox();
    //imagesGalleryManager.getInstance().initializeAlbumSortableBox();
});