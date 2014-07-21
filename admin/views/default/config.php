<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */
?>

<div class="wrap">

  <h2>Facebook album fetcher</h2>
  <p>Récupèrez les images d'une page facebook en un éclair. Pour cela, vous avez besoin de créer une application facebook sur <a href="http://developer.facebook.com">http://developer.facebook.com</a> 
    et récupèrer ses identifiants "App id" et "App secret". 
  </p>


<!-- <span class="spinner waiting-6" style="display: none;"></span> -->
  <div id="alert-message" class="updated settings-error <?php echo ($validIds) ? 'hide' : ''; ?>"> 
    <p><strong class="message"><?php echo isset($error) ? $error : ''; ?></strong></p>
  </div>


  <form method="post" action="" id="facebook-fetcher-config-form">
    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row"><label for="appId">App id</label></th>
          <td><input name="appId" type="text" id="appId" value="<?php echo (isset($ids)) ? $ids[FacebookAuthModel::FIELD_APP_ID] : ''; ?>" class="regular-text"></td>
        </tr>
        <tr>
          <th scope="row"><label for="appSecret">App secret</label></th>
          <td>
            <input name="appSecret" type="password" id="appSecret" value="<?php echo (isset($ids)) ? $ids[FacebookAuthModel::FIELD_APP_SECRET] : ''; ?>" class="regular-text">
            <p class="description">Récupérez les identifiants de votre application facebook sur <a href="http://developer.facebook.com">http://developer.facebook.com</a></p>
          </td>
        </tr>
        
      <tr>
        <th scope="row"><label for="pageId">Page id</label></th>
        <td>
          <input name="pageId" type="text" id="pageId" value="<?php echo (isset($pageId)) ? $pageId : ''; ?>" class="regular-text" placeholder="exemple : 336261643166071">
          <p class="description">Saisissez l'identifiant de la page Facebook des photos à récupèrer. </p>
        </td>
      </tr>


    </tbody>
  </table>


  <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Enregistrer mes identifiants"></p>
</form>

</div>



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
      var URL_SAVE_IDS_ACTION = 'facebook_fetcher_save_ids';
      var URL_CHECK_PAGE_EXIST_ACTION = 'facebook_fetcher_check_page';

      var fieldAppId = $('#appId');
      var fieldAppSecret = $('#appSecret');
      var fieldSaveIds = $('#saveIds');
      var fieldPageId = $('#pageId');

        
      var checkForm = function() {
       
      };


      var saveAppId = function(callBack) {
         var data = {
            action: URL_SAVE_IDS_ACTION,
            appId: fieldAppId.val(),
            appSecret: fieldAppSecret.val(),
            saveIds: fieldSaveIds.val(),
            pageId: fieldPageId.val()
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.post("admin-ajax.php", data, function(response) {
            callBack(response);
        });
      }

      var checkPageId = function(callBack) {
         var data = {
            action: URL_CHECK_PAGE_EXIST_ACTION,
            pageId: fieldPageId.val()
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.post("admin-ajax.php", data, function(response) {
            callBack(response);
        });
      }

      $('#facebook-fetcher-config-form').on('submit', function(e) {
        e.preventDefault();

        console.log('submit');

        checkForm();

        var alert = $.fn.albums.alert;

        alert.setMessage("Enregistrement et vérification de vos identifiants...");
        alert.setLoading(true);
        alert.setDisplay(true);

        saveAppId(function(response){

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

