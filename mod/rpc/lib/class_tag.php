<?php

    class Tag extends ElggObject
    {
        var $tag;
        var $tagtype;
        var $ref;
        var $access;
        var $type = 'tag';

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
                if ($result = get_record('tags','ident',$var)) {
                    $this->ident   = $result->ident;
                    $this->tag     = $result->tag;
                    $this->tagtype = $result->tagtype;
                    $this->ref     = $result->ref;
                    $this->access  = $result->access;
                    $this->owner   = $result->owner;
                    
                    // Does the requested id exist
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
        function setTagName($val)
        {
            $this->tag = trim($val);
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
                return delete_records('tags','ident',$this->ident);
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
            $t = new StdClass;
            $t->tagtype = 'weblog';
            $t->access = $this->access;
            $t->tag = $this->tag;
            $t->ref = $this->ref;
            $t->owner = $this->owner;
            // Always delete existing tags
            if ($this->exists == false)
            {
                if ($this->ident = insert_record('tags',$t)) {
                    $this->exists = true;
                    return $this->ident;
                }
                return false;
            }
            else
            {
                $t->ident = $this->ident;
                if (update_record('tags',$t)) {
                    return $this->ident;
                }
                return false;
            }
        }
    }
?>
