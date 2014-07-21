<?php

class FacebookAuthModel
{

    const FIELD_APP_ID = 'appId';
    const FIELD_APP_SECRET = 'appSecret';
    const FIELD_PAGE_ID = 'pageId';

    private $facebook = null;
    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;


    public static function getInstance() {
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {

            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * [saveIds description]
     * @param  [type] $appId      [description]
     * @param  [type] $appSecret  [description]
     * @return [type]             [description]
     */
    public function setIds($appId, $appSecret) {
        update_option(FacebookAuthModel::FIELD_APP_ID, $appId);
        update_option(FacebookAuthModel::FIELD_APP_SECRET, $appSecret);
    }

    public function setPageId($pageId) {
        update_option(self::FIELD_PAGE_ID, $pageId);
    }

    public function getPageId() {
       // $this->initSession();   

        /* Trying to get in field */
        // if(isset($_SESSION['facebook-fetcher'][self::FIELD_PAGE_ID]) && $_SESSION['facebook-fetcher'][self::FIELD_PAGE_ID] != "") { /* Trying to get in session */

        //     $this->pageId = $_SESSION['facebook-fetcher'][self::FIELD_PAGE_ID];
        //     return $this->pageId;
        //     /* Trying to get in database */
        //     //TODO
        // } else {
        //     /* Trow an exception if no */
        //     throw new Exception("La page n'existent pas.", 1);
        // }
        return get_option(self::FIELD_PAGE_ID);
    }

    private function initSession() {
        if(!isset($_SESSION['facebook-fetcher']))
            $_SESSION['facebook-fetcher'] = array();
    }

    /**
     * Get id from session
     * If session doesn't exists, try to fetch in database
     * @return [type] [description]
     */
    public function getIds() {

        $ids = array(
            self::FIELD_APP_ID      => get_option(self::FIELD_APP_ID), 
            self::FIELD_APP_SECRET  => get_option(self::FIELD_APP_SECRET)
            );

        // $this->initSession();   


        // if ($id[self::FIELD_APP_ID] != "" && $id[self::FIELD_APP_SECRET] != ""){

        // }
        // /* Trying to get in field */
        // elseif(isset($_SESSION['facebook-fetcher'][self::FIELD_APP_ID]) && isset($_SESSION['facebook-fetcher'][self::FIELD_APP_SECRET])) { /* Trying to get in session */

        //     $ids[self::FIELD_APP_ID]        = $_SESSION['facebook-fetcher'][self::FIELD_APP_ID];
        //     $ids[self::FIELD_APP_SECRET]    = $_SESSION['facebook-fetcher'][self::FIELD_APP_SECRET];


        //     /* Trying to get in database */
        //     //TODO
        // } else {
        //     /* Trow an exception if no */
        //     throw new Exception("Les identifiants n'existent pas.", 1);
        // }
        return $ids;
    }

    public function hasValidIds() {

        $ids = $this->getIds();
        $appId = $ids[self::FIELD_APP_ID];
        $access_token = $this->getFacebook()->getAccessToken();

        $response = json_decode(file_get_contents("https://graph.facebook.com/oauth/access_token_info?client_id=$appId&access_token=$access_token"));

        if(isset($response->access_token))
            return true;
        else 
            return false;
    }

    public function getFacebook() {
        $ids = $this->getIds();
        if($this->facebook == null) {
            $this->facebook = new Facebook(array(
                'appId'  => $ids[self::FIELD_APP_ID],
                'secret' => $ids[self::FIELD_APP_SECRET],
                'cookie' => true
                ));
        }

        return $this->facebook;
    }

    public function isValidPage($pageId) {

        $ids = $this->getIds();
        $appId = $ids[self::FIELD_APP_ID];
        $access_token = $this->getFacebook()->getAccessToken();

        $response = json_decode(file_get_contents("https://graph.facebook.com/$pageId/?access_token=$access_token"));

        if(isset($response->id))
            return true;
        else 
            return false;
    }

}
