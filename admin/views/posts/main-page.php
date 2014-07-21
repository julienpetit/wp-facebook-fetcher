

<div id="post-page">

  <div class="container-posts loading">
    
  </div>

  <div class="container-form">


<form method="post" action="" id="facebook-fetcher-do-convert-post-form">
    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row"><label for="title">Saisissez votre titre</label></th>
          <td><input name="title" type="text" id="title" value="" class="regular-text"></td>
        </tr>
        <tr>
          <th scope="row"><label for="createdat">Date de publication</label></th>
          <td><input name="createdat" type="datetime" id="createdat" value="" class="regular-text"></td>
        </tr>

        
        <tr>
          <th scope="row"><label for="body">Corps de l'article</label></th>
          <td>
            <textarea name="body" id="body" class="regular-text">

<p><?php //echo $message; ?></p>
<div class="row">
  <div class="col-sm-4">
    <div class="thumbnail thumbnail-noborder">
      [image-0]
    </div>
  </div>
  <div class="col-sm-4">
    <div class="thumbnail thumbnail-noborder">
      [image-1]
    </div>
  </div>
</div>

            </textarea>
          </td>
        </tr>




    </tbody>
  </table>

  <div id="container-images">

  </div>


  <input type="hidden" name="postCreatedTime" id="postCreatedTime" value="<?php echo $createdTime; ?>" />
  <input type="hidden" name="postId" id="postId" value="<?php echo $postId; ?>" />

  <p class="submit"><input type="submit" name="submit" id="doConvertPost" class="button" value="Convertir"></p>
</form>


  </div>
</div>
















<script type="text/javascript">
(function($) {
    "use strict";


    // Form Instances
    var inputTitle = $('#facebook-fetcher-do-convert-post-form #title');
    var inputBody = $('#facebook-fetcher-do-convert-post-form #body');
    var inputCreatedAt = $('#facebook-fetcher-do-convert-post-form #createdat');
    var buttonDoConvertPost = $('#facebook-fetcher-do-convert-post-form #doConvertPost');

    // View Instances
    var contentImages = $('#container-images');

    // Model
    var model = {
      aid : '',
      title : '',
      createdAt : '',
      body : '',
      images : ''
    };

    // Views methods
    var updateView = function() {
      inputTitle.val(model.title);
      inputCreatedAt.val(model.createdAt);
    }

    var updateViewImages = function() {
      // Resetting view
      contentImages.html('');

      $.each(model.images, function(i, value) {
        contentImages.append("<div class='item-img'><img src='" + value + "' /><p>[image-" + i + "]</p></div>");
      });
    };

    // Init & Events
    $(function() {

      /**
       * Fetching posts list
       * @return {[type]} [description]
       */
      $(".container-posts").load('admin-ajax.php?action=facebook_fetcher_get_posts_list', function(){
        // Suppression du loader
        $(this).removeClass('loading');
      });

      /**
       * EVENT - Click on a post
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      $(".container-posts").delegate('.continue-with-this-post', 'click', function(e) {
        // Disabling button view
        buttonDoConvertPost.attr("disabled", "disabled");

        // Updating model
        model.aid = $(e.currentTarget).data('postid');
        model.title = $(e.currentTarget).data('postbody');
        model.createdAt = $(e.currentTarget).data('postcreatedat');
        
        $.get('admin-ajax.php?action=facebook_fetcher_get_post_images&postId='+model.aid, function(response){
          model.images = JSON.parse(response);
          updateViewImages();

          // Enabling button
          buttonDoConvertPost.removeAttr('disabled');
        });
        // Updating the view  
        updateView();
      });

      buttonDoConvertPost.on('click', function() {
        // Disabling button view
        buttonDoConvertPost.attr("disabled", "disabled");

        model.title = inputTitle.val();
        model.createdAt = inputCreatedAt.val();

        model.body = inputBody.val();

        // If the textarea is an ACE Editor
        if(typeof CodeMirror != "undefined") {
          var editor = $('.CodeMirror')[0].CodeMirror;
          model.body = editor.getValue();
        }

        $.post('admin-ajax.php?action=facebook_fetcher_do_convert_post', model, function(response){

          console.log(response);

          // Enabling button
          buttonDoConvertPost.removeAttr('disabled');
        });
      })



  

    });

}(jQuery));
</script>

