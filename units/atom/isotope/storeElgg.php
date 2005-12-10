<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * storeElgg.php - Elg storage of Atom entries.                       *
 *                Implements the storeAPI                             *
 *                                                                    *
 *********************************************************************/

include_once("storeBaseClass.php");

class ElggStorage extends BaseClassStorage {

	function init($config) {
		global $req;

/*
        if ($config["storeDir"]) {
        } else {
    		$req->addMsg("ElggStorage: not configured yet");
        }
*/
		$req->addMsg("ElggStorage: init");
		
	}


	function getAtomEntry($entryId) {
		global $req;
		$req->addMsg("BaseClassStorage: getAtomEntry not implemented yet");
	}


	function storeAtomEntry(&$entry) {
		global $req;
		$req->addMsg("BaseClassStorage: storeAtomEntry not implemented yet");
	}


	function deleteAtomEntry($entryId) {
			global $req;
			$req->addMsg("BaseClassStorage: deleteAtomEntry not implemented yet");
	}


	function getAtomEntries($first=10) {
		global $req;

        // TODO distinguish between blogs
        
        $userIdent = run("users:name_to_id", ELGG_USER);
        $user = run("users:instance", $userIdent);
        $weblog = run("weblogs:instance", array('user_id' => $userIdent,
                                               'blog_id' => $userIdent));

        $posts = array_slice($weblog->getPosts(), 0, $first);

        $feed = new AtomFeed();
        $feed->title = $weblog->getTitle();

		$feed->modified=0;

        if ($first >= 1)
        {
            if (sizeof($posts) > 0)
            {
                foreach($posts as $post_id)
                {
                    // Get post object
                    $post = $weblog->getPost($post_id);

                    // New entry
                    $atomEntry = new AtomFeedEntry();
            		$atomEntry->id       = $post_id;
            		$atomEntry->created  = date('Y-m-d\TH:i:s', $post->getPosted());
            		$atomEntry->modified = date('Y-m-d\TH:i:s', $post->getPosted());

                    $atomEntry->link     =  substr($post->getPermaLink(),0,-5);
            		$atomEntry->title    = $post->getTitle();
            		$atomEntry->issued   = date('Y-m-d\TH:i:s', $post->getPosted());
                    // Personal data
                    $person = new AtomPerson();
                    $person->name = $user->getName();
                    $person->url = url.ELGG_USER."/weblog";
                    $person->email = $user->getEmail();
                    
                    $atomEntry->author = $person;

                    $atomContent = new AtomContent();
                    $atomContent->containerType = "text";
                    $atomContent->container = "Here is the entry";
                    $atomEntry->content = $atomContent;
                    
            		$postUrl = $this->blogDirUrl;
            		
            		$req->addMsg("storeElgg: getAtomEntries");

                    // TODO modify service url for this post

                    // Add the entry to the feed
                    array_push($feed->entries, $atomEntry);
                }
            }
        }
		return $feed;
	}


	function getAtomComment($blogId, $commentId) {
		global $req;
		$req->addMsg("BaseClassStorage: getAtomComment not implemented yet");
		return $comment;
	}


	function storeAtomComment($blogId, &$comment) {
		global $req;
		$req->addMsg("BaseClassStorage: storeAtomComment not implemented yet");
	}


	function deleteAtomComment($blogId, $commentId) {
		global $req;
		$req->addMsg("BaseClassStorage: deleteAtomComment not implemented yet");
	}


	function getAtomComments($entryId) {
		global $req;
		$req->addMsg("BaseClassStorage: getAtomComments not implemented yet");
		return $feed;
	}

}


?>
