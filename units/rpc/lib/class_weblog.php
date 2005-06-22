<?php

    /**
     * Weblog class
     *
     * A class for representing an Elgg weblog.
     *
     * @author    Misja Hoebe <misja@efobia.nl>
     * @version   0.3
     * @copyright 2005, Misja Hoebe/Elgg project, GPL
     * @package   RPC
     */
    Class Weblog extends ElggObject
    {
        var $title;
        var $posts;

        var $blog_name;
        var $blog_username;
        var $blog_owner;

        var $user_id;
        var $user_name;
        var $user_username;

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
                $query = db_query("select ident from users where username = $user_id");
                $this->user_id = $query->ident;
            }

            if (is_numeric($blog_id))
            {
                $this->ident = $blog_id;
            }
            elseif (is_string($blog_id))
            {
                $query = db_query("select ident from users where username = $blog_id");
                $this->ident = $query->ident;
            }

            // Are we dealing with a person or a community?
            if (run("users:type:get", $this->ident) == "person")
            {
                $result = db_query("select users.name, 
                                    users.username, 
                                    weblog_posts.ident, 
                                    weblog_posts.weblog, 
                                    weblog_posts.access, 
                                    weblog_posts.posted, 
                                    weblog_posts.title 
                                    from users, weblog_posts 
                                    where users.ident = '$this->user_id' 
                                    and weblog_posts.owner = '$this->user_id' 
                                    and weblog_posts.weblog = '$this->user_id'
                                    order by weblog_posts.posted desc");

                $this->user_name     = $result[0]->name;
                $this->user_username = $result[0]->username;

                $this->blog_name     = $this->user_name;
                $this->blog_username = $this->user_username;
                $this->blog_owner    = $this->user_id;
            }
            else
            {
                // It's a community
                $this->community = true;

                // Get the owner
                $sql_owner = db_query("select owner from users where ident = $this->ident");
                $this->blog_owner = $sql_owner[0]->owner;

                // Inject an SQL restriction if the user is not owner
                $sql_insert = "";
                
                if ($this->blog_owner != $this->user_id)
                {
                    $sql_insert = " and weblog_posts.owner = $this->user_id ";
                }

                $result = db_query("select users.name, 
                                    users.username,
                                    users.owner, 
                                    weblog_posts.ident 
                                    from users, weblog_posts 
                                    where users.ident = $this->ident 
                                    and weblog_posts.weblog = $this->ident
                                    $sql_insert
                                    order by weblog_posts.posted desc");

                $this->blog_name     = $result[0]->name;
                $this->blog_username = $result[0]->username;
                
                $user = run('users:instance', array('user_id' => $this->user_id));
                
                $this->user_name     = $user->getName();
                $this->user_username = $user->getUserName();
            }


            if (sizeof($result) > 0)
            {
                foreach ($result as $post)
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
            return $this->blog_owner;
        }

        /**
         * Check if the user is the weblog owner
         *
         * @return boolean
         */
        function isOwner()
        {
            if ($this->blog_owner == $this->user_id)
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
    }
?>
