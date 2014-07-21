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

  <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

  <p>Récupérez les photos de vos albums Facebook.</p>


  <div id="album-images">

    <?php foreach ($albums as $key => $album) : ?>
    <div class="tile-album-image">
      <div class="album-image">
        <div class="thumbnail">

          <div class="centered">
            <?php if(isset($album->cover_photo)) : ?>
            <img src="http://graph.facebook.com/<?php echo $album->cover_photo; ?>/picture"></td>
          <?php endif; ?>
        </div>
      </div>

    </div>
    <div class="clearfix"></div>
    <h3>
      <a class="link-show-album" href="admin.php?page=facebook-fetcher-albums&amp;action=showalbum&amp;id=<?php echo $album->id; ?>" title=""><?php echo $album->name; ?></a>
    </h3>
    <p class="photos-count"><?php echo (isset($album->count)) ? $album->count : '0'; echo " photos"; ?></p>
  </div>

<?php endforeach; ?>

</div>


</div>

<script type="text/javascript">
(function($) {
    "use strict";

    $(function() {

        
      $('.album-image').on('click', function(e) {
        var link = $(this).parent().find('a.link-show-album').attr('href');
        window.location.href = link;
      });
    });

}(jQuery));
</script>
