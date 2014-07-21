<form method="post" action="" id="facebook-fetcher-do-convert-post-form">
    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row"><label for="title">Titre</label></th>
          <td><input name="title" type="text" id="title" value="" class="regular-text"></td>
        </tr>
        <tr>
          <th scope="row"><label for="message">App secret</label></th>
          <td>
            <textarea name="message" id="message" class="regular-text">

<p><?php echo $message; ?></p>
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

  <input type="hidden" name="postCreatedTime" id="postCreatedTime" value="<?php echo $createdTime; ?>" />
  <input type="hidden" name="postId" id="postId" value="<?php echo $postId; ?>" />

<?php foreach ($images as $key => $image) {
  echo "<input type='hidden' name='images[]' class='post-img' value='$image' />";
}
?>

  <p class="submit"><input type="submit" name="submit" id="doConvertPost" class="button button-primary" value="Convertir"></p>
</form>


<h2>Images</h2>
<?php foreach ($images as $key => $image) {
  echo "<p><img src='$image' width='100'/></p>";
}
?>









<script type="text/javascript">
(function($) {
    "use strict";


    $.fn.albums = {};

    $.fn.albums.alert = {
      div : $('#alert-message'),

      setLoading : function(load) {
        if(load)
          this.div.addClass("loading");
        else
          this.div.removeClass("loading");
      },

      setMessage : function(message) {
        this.div.find('.message').html(message);
      },

      setDisplay : function(display) {
        if(display)
          this.div.show();
        else
          this.div.hide();
      }
    }

    $(function() {
      var URL_DO_CONVERT = 'facebook_fetcher_do_convert_post';

      var form = '#facebook-fetcher-do-convert-post-form';
        
      var checkForm = function() {
       
      };


      var doConvert = function(callBack) {
         var data = {
            action: URL_DO_CONVERT,
        };

        $(form).find("input, textarea, select").each(function(i, obj) {
            data[obj.name] = $(obj).val();
        }); 

        data.images = [];
        $(form).find(".post-img").each(function(i, obj){
          // console.log(obj);
          data.images.push($(obj).val());
        })

        // var editor = $('.CodeMirror')[0].CodeMirror;
        // data.message = editor.getValue();

        console.log(data);



        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.post("admin-ajax.php", data, function(response) {
            callBack(response);
        });
      }

      $('#facebook-fetcher-do-convert-post-form').on('submit', function(e) {
        e.preventDefault();

        console.log('submit');

        checkForm();

        var alert = $.fn.albums.alert;

        alert.setMessage("Enregistrement et vérification de vos identifiants...");
        alert.setLoading(true);
        alert.setDisplay(true);

        doConvert(function(response){

          var resp = {};
          try {
            resp = JSON.parse(response);
            if(resp.error != undefined) {
              alert.setMessage(resp.error.message);
            } else if (resp.success != undefined) {
              alert.setMessage(resp.success.message);
              // window.location.href = "admin.php?page=facebook-fetcher-albums&pageId=" + fieldPageId.val();
            }
          }
          catch (e) {
            alert.setMessage("Erreur inconnue.");
          }
          console.log(response);

          alert.setLoading(false);
        });


      });
    });

}(jQuery));
</script>



