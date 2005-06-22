<?php
    Class Post extends ElggObject
    {
        var $owner;
        var $weblog;
        var $blog_id;
        var $access = 'PUBLIC';
        var $posted;
        var $title;
        var $body;
        var $tags;
        var $comments;

        var $exists;

        /**
         *
         */
        function Post($post_id = "default")
        {
            $this->exists = false;

            // Parameter passed, assume an existing post
            if ($post_id != "")
            {
                $post = db_query("select * from weblog_posts where ident = '$post_id'");

                $this->ident     = $post[0]->ident;
                $this->owner     = $post[0]->owner;
                $this->blog_id   = $post[0]->weblog;
                $this->access    = $post[0]->access;
                $this->posted    = $post[0]->posted;
                $this->title     = $post[0]->title;
                $this->body      = $post[0]->body;

                // Get the weblog context
                $this->weblog = run('weblogs:instance', array('user_id' => $this->owner,
                                                              'blog_id' => $this->blog_id));

                // Does the requested id exist
                if (sizeof($post) > 0)
                {
                    $this->exists = true;
                }

                $post_tags = db_query("select ident from tags where ref = '$post_id'");

                // An aray of Tag objects
                foreach ($post_tags as $tag)
                {
                    $this->tags[] = $tag->ident;
                }

                $post_comments = db_query("select ident from weblog_comments where post_id = '$post_id'");

                foreach ($post_comments as $comment)
                {
                    $this->comments[] = $comment->ident;
                }
            }
        }

        /**
         *
         */
        function getOwner()
        {
            return $this->owner;
        }

        /**
         *
         */
        function getCommunity()
        {
            return $this->community;
        }

        /**
         *
         */
        function getAccess()
        {
            return $this->access;
        }

        /**
         *
         */
        function getPosted()
        {
            return $this->posted;
        }

        /**
         *
         */
        function getTitle()
        {
            return $this->title;
        }

        /**
         *
         */
        function getBody()
        {
            return $this->body;
        }

        /**
         *
         */
        function getWeblog()
        {
            return $this->blog_id;
        }

        /**
         *
         */
        function getTags()
        {
            return $this->tags;
        }

        /**
         *
         */
        function deleteTags()
        {
            $value = false;

            foreach ($this->tags as $tag_id)
            {
                $tag = run('tags:instance', array('id' => $tag_id));
                $value = $tag->delete();
            }

            return $value;
        }

        /**
         *
         */
        function getTag($tag_id)
        {
            return run('tags:instance', array("id" => $tag_id));
        }

        /**
         *
         */
        function getComments()
        {
            return $this->comments;
        }
        
        /**
         *
         */
        function getComment($comment_id)
        {
            return run('comments:instance', array('id' => $comment_id));
        }

        /**
         *
         */
        function deleteComments()
        {
            $value = false;

            foreach ($this->comments as $comment_id)
            {
                $comment = run('comments:instance', array('id' => $comment_id));
                $value = $comment->delete();
            }

            return $value;
        }

        /**
         *
         */
        function getUrl()
        {
            return $this->weblog->getUrl() ."#" . $this->ident;
        }

        /**
         *
         */
        function getPermaLink()
        {
            return $this->weblog->getUrl() . $this->ident . ".html";
        }

        /**
         *
         */
        function setOwner($val)
        {
            $this->owner = $val;
        }

        /**
         *
         */
        function setCommunity($val)
        {
            $this->community = $val;
        }

        /**
         *
         */
        function setAccess($val)
        {
            $this->access = $val;
        }

        /**
         *
         */
        function setPosted($val)
        {
            $this->posted = $val;
        }

        /**
         *
         */
        function setTitle($val)
        {
            $this->title = addslashes($val);
        }

        /**
         *
         */
        function setBody($val)
        {
            $this->body = addslashes($val);
        }

        /**
         *
         */
        function setWeblog($val)
        {
            $this->blog_id = $val;
        }

        /**
         *
         */
        function delete()
        {
            if ($this->exists)
            {
                // Check ownership
                if ($this->weblog->isOwner() != true)
                {
                    // Not weblog owner, check at post level
                    if ($this->owner != $this->weblog->getOwner())
                    {
                        return false;
                    }
                }

                // Remove related objects

                // Remove tags
                $this->deleteTags();

                // Remove comments
                $this->deleteComments();

                db_query("delete from weblog_posts where ident = '$this->ident'");

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

        /**
         *
         */
        function save()
        {
            if ($this->exists == true)
            {
                // Check ownership
                if ($this->weblog->isOwner() != true)
                {
                    // Not weblog owner, check at post level
                    if ($this->owner != $this->weblog->getOwner())
                    {
                        return false;
                    }
                }

                // Owner is still unmutable
                db_query("update weblog_posts set 
                          title = '$this->title', 
                          body = '$this->body', 
                          access = '$this->access', 
                          where ident = $this->ident");

                if (db_affected_rows() > 0)
                {
                    return $this->ident;
                }
                else
                {
                    return false;
                }    
            }
            else
            {
                db_query("insert into weblog_posts set 
                          title = '$this->title',
                          body = '$this->body',
                          weblog = $this->blog_id, 
                          access = '$this->access', 
                          posted = ".time().", 
                          owner = $this->owner");

                if (db_affected_rows() > 0)
                {
                    // Set the new post id
                    $this->ident = db_id();
                    $this->exists = true;

                    return $this->ident;
                }
                else
                {
                    return false;
                }
            }
        }
    }

?>
