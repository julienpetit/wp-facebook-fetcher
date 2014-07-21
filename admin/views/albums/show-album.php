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


  <nav class="plugin-navigation">
    <button class="button button-primary" id="album-button-import-selection">Importer la sélection</button>
    <button class="button button-default" id="album-button-select-all">Tout sélectionner</button>


  </nav>
  <div>
    <ul class="subsubsub">
      <li class="all"><strong>Sélectionnés</strong> (<span class="selected-count">0</span>)</li>
    </ul>
    <div class="clearfix"></div>
  </div>

  <div class="clearfix"></div>
  <div id="album-images">

    <?php foreach ($photos as $key => $photo) : ?>
    <div class="album-image">
      <a class="check" href="#" title="Désélectionner"><div class="media-modal-icon"></div></a>
      <span class="message"></span>
      <div class="thumbnail">

        <div class="centered">
          <img src="<?php echo $photo->source; ?>" data-fbid="<?php echo $photo->id; ?>">
        </div>
      </div>
    </div>

  <?php endforeach; ?>

</div>

</div>