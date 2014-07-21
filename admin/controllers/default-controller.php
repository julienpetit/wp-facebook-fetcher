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
class FacebookFetcherDefaultController extends JPController {

  /**
   * Render the settings page for this plugin.
   *
   * @since    1.0.0
   */
  public function defaultAction() {


    $facebookAuth = FacebookAuthModel::getInstance();


    $validIds = false;
    try {
      $ids = $facebookAuth->getIds();
      $pageId = $facebookAuth->getPageId();

      $validIds = ($facebookAuth->hasValidIds()) ? true : false;

      if(!$validIds)
        $error = "Attention. Vos identifiant App id et App secret ne sont pas valides. Veuillez les vérifier puis les enregistrer à nouveau."; 
    } catch (Exception $e) {
      $error = $e->getMessage();
    }

    include_once( plugin_dir_path(__FILE__) . '../views/default/config.php' );
  }

  public function ajaxSaveIdsAction() {

    $appId      = esc_attr($_POST['appId']);
    $appSecret  = esc_attr($_POST['appSecret']);
    $pageId     = esc_attr($_POST['pageId']);

    $facebookAuth = FacebookAuthModel::getInstance();
    $facebookAuth->setIds($appId, $appSecret);
    $facebookAuth->setPageId($pageId);

    try {
      // Exception when ids aren't valid
      if(!$facebookAuth->hasValidIds())
        throw new Exception("Attention. Vos identifiant App id et App secret ne sont pas valides. Veuillez les vérifier puis les enregistrer à nouveau.", 1);

      // Exception when page isn't valid
      if(!$facebookAuth->isValidPage($pageId))
        throw new Exception("Attention. La page '$pageId' est invalide.", 1);

    } catch (Exception $e) {
      echo json_encode(array(
        'error' => array(
          'message' => $e->getMessage(),
          'code' => $e->getCode(),
          ),
        ));
      exit();
    }
   

    echo json_encode(array(
      'success' => array(
        'message' => "Vos identifiants sont valides." 
        )
      ));

    exit();
  }
}