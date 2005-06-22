<?php

    class Tag extends ElggObject
    {
        var $tag;
        var $tagtype;
        var $ref;
        var $access;
        var $owner;

        var $exists;

        /**
         *
         */
        function Tag($var = "default")
        {
            $this->exists = false;

            // Parameter passed, assume an existing tag
            if ($var != "")
            {
                $result = db_query("select * from tags where ident = '$var'");
            
                $this->ident   = $result[0]->ident;
                $this->tag     = $result[0]->tag;
                $this->tagtype = $result[0]->tagtype;
                $this->ref     = $result[0]->ref;
                $this->access  = $result[0]->access;
                $this->owner   = $result[0]->owner;

                // Does the requested id exist
                if (sizeof($result) > 0)
                {
                    $this->exists = true;
                }
            }
        }

        /**
         *
         */
        function getTagName()
        {
            return $this->tag;
        }

        /**
         *
         */
        function getTagType()
        {
            return $this->tagtype;
        }

        /**
         *
         */
        function getRef()
        {
            return $this->ref;
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
        function getOwner()
        {
            return $this->owner;
        }

        /**
         *
         */
        function setTagName($val)
        {
            $this->tag = addslashes(trim($val));
        }

        /**
         *
         */
        function setTagType($val = "weblog")
        {
            $this->tagtype = $val;
        }

        /**
         *
         */
        function setRef($val)
        {
            $this->ref = $val;
        }

        /**
         *
         */
        function setAccess($val = "PUBLIC")
        {
            $this->access = $val;
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
        function delete()
        {
            if ($this->exists == true)
            {
                db_query("delete from tags where ident = '$this->ident'");

                if (db_affected_rows() > 0)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }

        /**
         *
         */
        function save()
        {
            // Always delete existing tags
            if ($this->exists == false)
            {
                db_query("insert into tags set 
                          tagtype = 'weblog', 
                          access = '$this->access', 
                          tag = '$this->tag', 
                          ref = $this->ref, 
                          owner = '$this->owner'");

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
                db_query("update tags set 
                          tagtype = 'weblog', 
                          access = '$this->access', 
                          tag = '$this->tag', 
                          ref = $this->ref 
                          where ident = $this->ident");

                if (db_affected_rows() > 0)
                {
                    $this->ident  = db_id();

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
