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
class FacebookFetcherPostController extends JPController {

  /**
   * Render the settings page for this plugin.
   *
   * @since    1.0.0
   */
  public function defaultAction() {

    //$pageId = esc_attr($_GET['pageId']);

    $this->addStyle('assets/css/posts/style.css');

    

    include_once( plugin_dir_path(__FILE__) . '../views/posts/main-page.php' ); 
  }

  public function getPostsListPartialAction() {
    $facebookAuth = FacebookAuthModel::getInstance();

    try {
      // Exception when ids aren't valid
      if(!$facebookAuth->hasValidIds())
        throw new Exception("Attention. Vos identifiant App id et App secret ne sont pas valides. Veuillez les vérifier puis les enregistrer à nouveau.", 1);

      $pageId = $facebookAuth->getPageId();
      // Exception when page isn't valid
      if(!$facebookAuth->isValidPage($pageId))
        throw new Exception("Attention. La page '$pageId' est invalide.", 1);

      $facebookModel = FacebookPostModel::getInstance($facebookAuth->getFacebook());
      $posts = $facebookModel->findAll($pageId);

    } catch (Exception $e) {
      echo json_encode(array(
        'error' => array(
          'message' => $e->getMessage(),
          'code' => $e->getCode(),
          ),
        ));
      exit();
    }

    include_once( plugin_dir_path(__FILE__) . '../views/posts/list-posts.php' );
  }


  public function getPostImagesAction() {
    $postId = $_GET['postId'];

    // echo $postId;

     $facebookAuth = FacebookAuthModel::getInstance();

    try {
      // Exception when ids aren't valid
      if(!$facebookAuth->hasValidIds())
        throw new Exception("Attention. Vos identifiant App id et App secret ne sont pas valides. Veuillez les vérifier puis les enregistrer à nouveau.", 1);

      $pageId = $facebookAuth->getPageId();
      // Exception when page isn't valid
      if(!$facebookAuth->isValidPage($pageId))
        throw new Exception("Attention. La page '$pageId' est invalide.", 1);

      $facebookModel = FacebookPostModel::getInstance($facebookAuth->getFacebook());
      $images = $facebookModel->getImagesUrlsFromPost($postId);

      echo json_encode($images);
      exit();
    } catch (Exception $e) {
      echo json_encode(array(
        'error' => array(
          'message' => $e->getMessage(),
          'code' => $e->getCode(),
          ),
        ));
      exit();
    }
    
  }

  // public function convertPostAction() {
  //   $postId = $_GET['postId'];
  //   $message = $_GET['postMessage'];
  //   $createdTime = $_GET['postCreatedTime'];

  //   $facebookAuth = FacebookAuthModel::getInstance();

  //   try {
  //     // Exception when ids aren't valid
  //     if(!$facebookAuth->hasValidIds())
  //       throw new Exception("Attention. Vos identifiant App id et App secret ne sont pas valides. Veuillez les vérifier puis les enregistrer à nouveau.", 1);

  //     $pageId = $facebookAuth->getPageId();
  //     // Exception when page isn't valid
  //     if(!$facebookAuth->isValidPage($pageId))
  //       throw new Exception("Attention. La page '$pageId' est invalide.", 1);

  //     $facebookModel = FacebookPostModel::getInstance($facebookAuth->getFacebook());
  //     $images = $facebookModel->getImagesUrlsFromPost($postId);

  //     // print_r_html($images);

  //   } catch (Exception $e) {
  //     echo json_encode(array(
  //       'error' => array(
  //         'message' => $e->getMessage(),
  //         'code' => $e->getCode(),
  //         ),
  //       ));
  //     exit();
  //   }
    
  //   include_once( plugin_dir_path(__FILE__) . '../views/posts/convert-post.php' );

  // }

  public function doConvertPostAction() {

    $body = $_POST['body'];

    $images = $_POST['images'];
    $date = $_POST['createdAt'];
    $title = $_POST['title'];

    $facebookAuth = FacebookAuthModel::getInstance();

    try {
      // Exception when ids aren't valid
      if(!$facebookAuth->hasValidIds())
        throw new Exception("Attention. Vos identifiant App id et App secret ne sont pas valides. Veuillez les vérifier puis les enregistrer à nouveau.", 1);

      $pageId = $facebookAuth->getPageId();
      // Exception when page isn't valid
      if(!$facebookAuth->isValidPage($pageId))
        throw new Exception("Attention. La page '$pageId' est invalide.", 1);

      $facebookModel = FacebookPostModel::getInstance($facebookAuth->getFacebook());

      $postId = $facebookModel->convertPostFacebookToWordpress($title, $body, $images, $date);

      echo $postId;
      switch ($postId) {
        case -1:
          # code...
        break;
        
        case -2:
          // Error
        break;
        
        default:
          # code...
        break;
      }

    } catch (Exception $e) {
      echo json_encode(array(
        'error' => array(
          'message' => $e->getMessage(),
          'code' => $e->getCode(),
          ),
        ));
      exit();
    }


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