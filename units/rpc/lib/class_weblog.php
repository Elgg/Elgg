<?php

    /**
     * Weblog class
     *
     * A class for representing an Elgg weblog.
     *
     * @author    Misja Hoebe <misja@efobia.nl>
     * @version   0.4
     * @copyright 2005, Misja Hoebe/Elgg project, GPL
     * @package   RPC
     */
    Class Weblog extends ElggObject
    {
        var $title;
        var $posts;

        var $blog_name;
        var $blog_username;
        var $owner;

        var $user_id;
        var $user_name;
        var $user_username;

        var $type = 'weblog';
        var $community;

        /**
         * Weblog class constructor
         *
         * <p>Will set all weblog properties, if the provided weblog id exist 
         * (which effectively will be a user id, regardless if one is dealing 
         * with a person or a community - for Elgg both are users).</p>
         * 
         * @param int $user_id The user id.
         * @param int $blog_id The weblog id.
         */
        function Weblog($user_id, $blog_id)
        {
            $this->community = false; // dealing with community or not

            // username/id conversions
            if (is_numeric($user_id))
            {
                $this->user_id = $user_id;
            }
            elseif (is_string($user_id))
            {
                $this->user_id = user_info_username('ident',$user_id);
            }

            if (is_numeric($blog_id))
            {
                $this->ident = $blog_id;
            }
            elseif (is_string($blog_id))
            {
                $this->ident = user_info_username('ident',$blog_id);
            }

            // Are we dealing with a person or a community?
            if (user_type($this->ident) == "person")
            {
                if ($result = get_record('users','ident',$this->user_id)) {
                    $this->user_name     = $result->name;
                    $this->user_username = $result->username;
                }

                $posts = get_records_select('weblog_posts',"owner = ? AND weblog = ?",array($this->user_id,$this->user_id),'posted DESC');

                $this->blog_name     = $this->user_name;
                $this->blog_username = $this->user_username;
                $this->owner         = $this->user_id;
            }
            else
            {
                // It's a community
                $this->community = true;

                // Get the owner
                $this->owner = user_info('owner',$this->ident);

                // Inject an SQL restriction if the user is not owner
                $sql_insert = "";
                
                if ($this->owner != $this->user_id)
                {
                    $sql_insert = " and owner = $this->user_id ";
                }

                if ($result = get_record('users','ident',$this->ident)) {
                    $this->blog_name     = $result->name;
                    $this->blog_username = $result->username;
                }
                
                $posts = get_records_select('weblog_posts',"weblog = $this->ident $sql_insert",null,'posted DESC');

                $user = run('users:instance', array('user_id' => $this->user_id));
                
                $this->user_name     = $user->getName();
                $this->user_username = $user->getUserName();
            }

            $this->posts = array();
            if (is_array($posts) && sizeof($posts) > 0)
            {
                foreach ($posts as $post)
                {
                    $this->posts[] = $post->ident;
                }
            }
            else
            {
            }
        }

        /**
         * Get weblog posts
         *
         * @return array An array with post id's.
         */
        function getPosts()
        {
            return $this->posts;
        }

        /**
         * Return a post object.
         *
         * Utility function. This will return a post object for the given post id.
         *
         * @param int $post_id The post id.
         * @return Post A Post object.
         */
        function getPost($post_id)
        {
            return run('posts:instance', array("id" => $post_id));
        }

        /**
         * Return the weblog owner id
         *
         * @return int The weblog owner id.
         */
        function getOwner()
        {
            return $this->owner;
        }

        /**
         * Check if the user is the weblog owner
         *
         * @return boolean
         */
        function isOwner()
        {
            if ($this->owner == $this->user_id)
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        /**
         * Get the weblog title
         *
         * @return string The weblog title.
         */
        function getTitle()
        {
            if ($this->community == true)
            {
                return $this->user_name . " @ " . $this->blog_name;
            }
            else
            {
                return $this->user_name . " :: Weblog";
            }
        }

        /**
         * Get the weblog name
         *
         * Will be the weblog's owner's name.
         *
         * @return string The weblog owner name.
         */
        function getName()
        {
            return $this->blog_name;
        }

        /**
         * Get the weblog URL
         *
         * @return string The weblog URL.
         */
        function getUrl()
        {
            return url . $this->blog_username . "/weblog/";
        }

        /**
         * Get the Atom feed URL
         *
         * @return string The servce.feed URL.
         */
        function getAtomFeedUrl()
        {
            return url . "atom/" . $this->ident;
        }

        /**
         * Get the Atom post URL
         *
         * @return string The service.post URL.
         */
        function getAtomPostUrl()
        {
            return url . "atom/" . $this->ident;
        }
    }
?>
