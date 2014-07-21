<?php
/**
 * Plugin Name.
 *
 * @package   FacebookFetcher_Admin
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package FacebookFetcher_Admin
 * @author  Your Name <email@example.com>
 */
class FacebookFetcherAlbumController extends JPController {

  /**
   * Render the settings page for this plugin.
   *
   * @since    1.0.0
   */
  public function defaultAction() {
    $this->showAlbumsAction();
  }

  public function showAlbumsAction() {

    //$pageId = esc_attr($_GET['pageId']);

    $this->addStyle('assets/css/albums/album.css');

    $facebookAuth = FacebookAuthModel::getInstance();

    try {
      // Exception when ids aren't valid
      if(!$facebookAuth->hasValidIds())
        throw new Exception("Attention. Vos identifiant App id et App secret ne sont pas valides. Veuillez les vérifier puis les enregistrer à nouveau.", 1);

      $pageId = $facebookAuth->getPageId();
      // Exception when page isn't valid
      if(!$facebookAuth->isValidPage($pageId))
        throw new Exception("Attention. La page '$pageId' est invalide.", 1);

      $facebookModel = FacebookAlbumModel::getInstance($facebookAuth->getFacebook());
      $albums = $facebookModel->findAlbums($pageId);

    } catch (Exception $e) {
      echo json_encode(array(
        'error' => array(
          'message' => $e->getMessage(),
          'code' => $e->getCode(),
          ),
        ));
      exit();
    }


    include_once( plugin_dir_path(__FILE__) . '../views/albums/albums-fetch.php' );
  }

  public function showAlbumAction() {
    $this->addScript('assets/js/albums/show-album.js');
    $this->addStyle('assets/css/albums/album.css');

    wp_enqueue_style('media-views');

    $id = esc_attr($_GET['id']);
    

     $facebookAuth = FacebookAuthModel::getInstance();

    try {
      // Exception when ids aren't valid
      if(!$facebookAuth->hasValidIds())
        throw new Exception("Attention. Vos identifiant App id et App secret ne sont pas valides. Veuillez les vérifier puis les enregistrer à nouveau.", 1);

      $facebookModel = FacebookAlbumModel::getInstance($facebookAuth->getFacebook());
      $photos = $facebookModel->findMediasFromAlbum($id);

    } catch (Exception $e) {
      echo json_encode(array(
        'error' => array(
          'message' => $e->getMessage(),
          'code' => $e->getCode(),
          ),
        ));
      exit();
    }




    include_once( plugin_dir_path(__FILE__) . '../views/albums/show-album.php' );
  }

  public function ajaxUploadImageAction() {
    $imgSrc = esc_attr($_POST['imgSrc']);
    $fbid = esc_attr($_POST['fbid']);

    $uploads = wp_upload_dir();
    $filePath = $uploads['path'] . "/" . $fbid . ".jpg";

    if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );

    $movefile = media_sideload_image($imgSrc, 0);
    if ( $movefile ) {
      echo "File is valid, and was successfully uploaded.\n";
      var_dump( $movefile);
    } else {
      echo "Possible file upload attack!\n";
    }



    exit();
  }

  public function testAction() {

    $this->addScript('assets/js/admin.js');

    // $id = esc_attr($_GET['id']);
    
    // $facebookModel = FacebookAlbumModel::getInstance();
    // $photos = $facebookModel->findMediasFromAlbum($id);

    // print_r_html($photos[0]);


    // include_once( plugin_dir_path(__FILE__) . '../views/albums/show-album.php' );
  }


}