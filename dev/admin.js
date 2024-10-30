(function($){
  "use strict";
  // Set all variables to be used in scope
  $(document).ready(function(){
    $('body').on('click', '.c4d-image-widget-select-image .upload', function(event){
      event.preventDefault();
      var frame,
      metaBox = $(this).parents('.c4d-image-widget-select-image'),
      addImgLink = metaBox.find('.upload'),
      delImgLink = metaBox.find( '.remove'),
      imgContainer = metaBox.find( '.image-display'),
      imgIdInput = metaBox.find( '.image-value' );
      // If the media frame already exists, reopen it.
      if ( frame ) {
        frame.open();
        return;
      }
      
      // Create a new media frame
      frame = wp.media();

      
      // When an image is selected in the media frame...
      frame.on( 'select', function() {
        
        // Get media attachment details from the frame state
        var attachment = frame.state().get('selection').first().toJSON();

        // Send the attachment URL to our custom image input field.
        imgContainer.append( '<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>' );

        // Send the attachment id to our hidden input
        imgIdInput.val( attachment.id );

        // Hide the add image link
        addImgLink.addClass( 'hidden' );

        // Unhide the remove image link
        delImgLink.removeClass( 'hidden' );
      });

      // Finally, open the modal on click
      frame.open();
    });
    
    // DELETE IMAGE LINK
    $('body').on( 'click', '.c4d-image-widget-select-image .remove', function( event ){

      event.preventDefault();
      var metaBox = $(this).parents('.c4d-image-widget-select-image'),
      addImgLink = metaBox.find('.upload'),
      delImgLink = metaBox.find( '.remove'),
      imgContainer = metaBox.find( '.image-display'),
      imgIdInput = metaBox.find( '.image-value' );

      // Clear out the preview image
      imgContainer.html( '' );

      // Un-hide the add image link
      addImgLink.removeClass( 'hidden' );

      // Hide the delete image link
      delImgLink.addClass( 'hidden' );

      // Delete the image id from the hidden input
      imgIdInput.val( '' );

    });
  });
})(jQuery);