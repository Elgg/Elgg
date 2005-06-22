<?php

    Class Comment extends ElggObject
    {
        var $post_id;
        var $owner;
        var $postedname;
        var $body;
        var $posted;

        var $exists;

        function Comment($var = 'default')
        {
            $this->exists = false;

            if ($var != "")
            {
                $comment = db_query("select * from weblog_comments where ident = '$var'"); 

                $this->ident      = $comment[0]->ident;
                $this->post_id    = $comment[0]->post_id;
                $this->owner      = $comment[0]->owner;
                $this->postedname = $comment[0]->postedname;
                $this->body       = $comment[0]->body;
                $this->posted     = $comment[0]->posted;

                // Does the requested id exist
                if (sizeof($comment) > 0)
                {
                    $this->exists = true;
                }
            }
            else
            {
                $this->exists = false;
            }
        }

        function getPostId()
        {
            return $this->post_id;
        }

        function getOwner()
        {
            return $this->owner;
        }

        function getPostedName()
        {
            return $this->postedname;
        }

        function getBody()
        {
            return $this->body;
        }

        function getPosted()
        {
            return $this->posted;
        }

        function delete()
        {
            db_query("delete from weblog_comments where ident = '$this->ident'");

            if (db_affected_rows() > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

?>
