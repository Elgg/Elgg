<?php

    Class Comment extends ElggObject
    {
        var $post_id;
        var $owner;
        var $postedname;
        var $body;
        var $posted;
        var $type = 'comment';

        var $exists;

        function Comment($var = 'default')
        {
            $this->exists = false;

            if ($var != "")
            {

                if ($comment = get_record('weblog_comments','ident',$var)) {
                    $this->ident      = $comment->ident;
                    $this->post_id    = $comment->post_id;
                    $this->owner      = $comment->owner;
                    $this->postedname = $comment->postedname;
                    $this->body       = $comment->body;
                    $this->posted     = $comment->posted;
                    $this->exists = true;
                } else {
                    $this->exists = false;
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
            return delete_records('weblog_comments','ident',$this->ident);
        }
    }

?>
