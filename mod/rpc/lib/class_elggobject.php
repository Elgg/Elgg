<?php
    Class ElggObject
    {
        var $ident;
        var $owner;
        var $_tag_uri;

        function ElggObject()
        {
        }

        /**
         * Get the object's id
         *
         * @return int The object id.
         */
        function getIdent()
        {
            return $this->ident;
        }

        /**
         * Get the object's owner id
         *
         * @return int The owner id
         */
        function getOwner()
        {
            return $this->getOwner();
        }

        /*
         * Get the object's tag URI
         *
         * @return string The object's tag URI
         */
        function getTagUri()
        {
            if (!isset($this->user_username) || $this->user_username == "")
            {
                if ($this->owner == -1)
                {
                    $user = new User($this->ident);
                }
                else
                {
                    $user = new User($this->owner);
                }

                $this->user_username = $user->getUserName();
            }

            // TODO handle the year

            $url = parse_url(url);

            $this->_tag_uri = "tag:".$this->user_username."@".$url['host'].",2005:".$this->type.":".$this->ident;

            return $this->_tag_uri;
        }
    }
?>
