<?php

class FacebookPostModel
{

    public static $facebook = null; 


    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;


    public static function getInstance($facebook) {
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {

            self::$instance = new self();
            self::$facebook = $facebook;
        }

        return self::$instance;
    }

    public function __construct() {
    }

    public function findAll($search_uid) {

        // Access token (previously got from https://developers.facebook.com/tools/access_token/)
        $access_token = self::$facebook->getAccessToken();

        //Get the list of albums
        $response = json_decode(file_get_contents("https://graph.facebook.com/v2.0/$search_uid/posts?access_token=$access_token&fields=id,message,link,picture"));
        $data = $response->data;

        // $data = self::$facebook->api('/')

        return $data;
    }

    public function getImagesUrlsFromPost($aid) {

        $access_token = self::$facebook->getAccessToken();
        // Get the attachments

        $response = json_decode(file_get_contents("https://graph.facebook.com/v2.2/$aid?access_token=$access_token&fields=attachments"));


        $images = array();

        if(isset($response->attachments)) {
            foreach ($response->attachments->data[0]->subattachments->data as $key => $value) {
//                print_r($value);

                $images[] = $value->media->image->src;
            }
        }


        /* $data = self::$facebook->api(array(
            'method' => 'fql.query',
            'query' => "SELECT attachment FROM stream WHERE post_id='$aid'",
            ));

        foreach ($data[0]['attachment']['media']  as $key => $value) {
            // print_r_html($value);
            // 536632726462294
            print_r($value);

            $test = self::$facebook->api("/".$value['photo']['fbid']);
            // print_r_html($test);

            $images[] = $test['images'][0]['source'];
        } */

        return $images;
    }


    public function convertPostFacebookToWordpress($title, $message, $images, $date) {

        // Get images ... 
        $slug = sanitize_title($title);

        foreach ($images as $key => $image) {

            if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );

            $movefile = media_sideload_image($image, 0);
            if ( $movefile ) {
              //echo "File is valid, and was successfully uploaded.\n";
              //print_r($movefile);


                $message = str_replace("[image-$key]", $movefile, $message);
            } else {
              //echo "Possible file upload attack!\n";

              return -3;
            }

        }





        // Initialize the page ID to -1. This indicates no action has been taken.
        $post_id = -1;

        // Setup the author, slug, and title for the post
        $author_id = 1;


        // If the page doesn't already exist, then create it
        if( null == get_page_by_title( $title ) ) {


            // Set the post ID so that we know the post was created successfully
            $post_id = wp_insert_post(
                array(
                    'comment_status'    =>  'closed',
                    'ping_status'       =>  'closed',
                    'post_author'       =>  $author_id,
                    'post_name'     =>  $slug,
                    'post_title'        =>  $title,
                    'post_status'       =>  'publish',
                    'post_type'     =>  'post',
                    'post_date'     => $date,
                    'post_content' => $message
                    )
                );

        // Otherwise, we'll stop
        } else {

                // Arbitrarily use -2 to indicate that the page with the title already exists
            $post_id = -2;

        } // end if

        return $post_id;
    }




}
