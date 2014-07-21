<?php

class FacebookAlbumModel
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

    public function findAlbums($search_uid) {

        // Access token (previously got from https://developers.facebook.com/tools/access_token/)
        $access_token = self::$facebook->getAccessToken();


        //Get the list of albums
        $response = json_decode(file_get_contents("https://graph.facebook.com/$search_uid/albums?access_token=$access_token&limit=999&fields=id,name,description,privacy,created_time,count,cover_photo"));
        $albums = $response->data;

        return $albums;
    }

    public function findMediasFromAlbum($aid) {

        $access_token = self::$facebook->getAccessToken();

        //Try to fetch the album object from Facebook, and check for common errors.
        $album_fetch_url = "https://graph.facebook.com/$aid?access_token=$access_token&fields=id,cover_photo,count,link,name,from,created_time,description";
        $album = json_decode(file_get_contents($album_fetch_url));
        if(!$album || isset($album->error))
        {
            if(!$album)                       $retVal['content'] = "An unknown error occurred while trying to fetch the album (empty reply).";
            else if($album->error->code==190) $retVal['content'] = "Error 190: Invalid OAuth Access Token.  Try using the admin panel to re-validate your plugin.";
            else if($album->error->code==803) $retVal['content'] = "Error 803: Your album id doesn't appear to exist.";
            else if($album->error->code==100) $retVal['content'] = "Error 100: Your album id doesn't appear to be accessible.";
            return $retVal;
        }
        if(!isset($album->id) || $album->id != $aid)
        {
            $retVal['content'] = "An unknown error occurred while trying to fetch the album (id mismatch).";
            return $retVal;
        }
        if(!isset($album->cover_photo) || $album->id != $aid)
        {
            $retVal['content'] = "An error occurred while trying to fetch the album: the ID specified does not appear to be an album.";
            return $retVal;
        }
        if($album->count == 0)
        {
            $retVal['content'] = "An error occurred while trying to fetch the album: it appears to be empty.";
            return $retVal;
        }


        //Now that we know the album is OK, try to fetch its photos.  Note that as of Feb 2014, it seems like Facebook
        //won't return more than 100 photos, so I'll have to fetch them in paged groups...
        $photos = Array();
        $photoGroupNum = 0;
        $debugString = "Starting to fetch $album->count photos.\nAlbum: <a href='$album_fetch_url'>$album_fetch_url</a>\n";
        $debugPhotoCount = 0;
        $fetch_url = "https://graph.facebook.com/$aid/photos?access_token=$access_token&limit=9999&fields=name,source,picture";
        while(true)
        {
            //Fetch this group (as many as FB will give us at once...might not be all of them, even though I specify a limit of 9999)
            $photosThisGroup = json_decode(file_get_contents($fetch_url));
            
            //Make sure no error
            if(!$photosThisGroup || !isset($photosThisGroup->data))
            {
                $retVal['content'] = "An unknown error occurred while trying to fetch the photos (empty data).";
                return $retVal;
            }
            
            //Just for testing...
            $debugString .= "**********************************************\n";
            $debugString .= "Group: $photoGroupNum\nFetch URL: <a href='$fetch_url'>$fetch_url</a>\nItems: " . count($photosThisGroup->data) . "\n";
            $debugString .= "**********************************************\n";
            foreach($photosThisGroup->data as $photo) $debugString .= ($debugPhotoCount++) . ") <a href='$photo->source'>$photo->source</a>\n";     
            $debugString .= "\n\n";
            
            //If we didn't get any back, we must've already fetched all available photos - break out of this loop.
            //I don't think this should ever happen, but check just in case (to avoid infinite loop)
            if(count($photosThisGroup->data) == 0)
            {
                $debugString .= "--->Done: No results returned.";
                break;
            }
            
            //Likewise - just be sure there's no infinite loop.  I'm pretty sure no album will ever have >2000 photos.
            if($photoGroupNum >= 20)
            {
                $debugString .= "--->Done: Stopped to prevent infinite loop (Limit: 2000 photos).";
                break;
            }

            //Tack these results onto our 'main overall' set of photos
            $photos = array_merge($photos, $photosThisGroup->data);
            
            //If we've got the total expected number of photos, we're done - break out of this loop
            if( count($photos) == $album->count )
            {
                $debugString .= "--->Done: Successfully fetched all " . count($photos) . " photos.";
                break;
            }
            
            //If the next 'paging' url isn't specified, it's telling us there are no more photos available - break out of this loop
            if(!isset($photosThisGroup->paging->next))
            {
                $debugString .= "--->Done: Paging->next wasn't set.";
                break;          
            }
            
            //Otherwise, get the URL to fetch the next group of photos & keep going.
            $fetch_url = $photosThisGroup->paging->next;
            $photoGroupNum++;

        }

        return $photos;
    }




}
