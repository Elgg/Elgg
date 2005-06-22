<?php

    Class User extends ElggObject
    {
        // Class variables have public access. With PHP5 and up they can be declared private.
        
        var $username;
        var $email;
        var $name;
        var $alias;
        var $code;
        var $icon_quota;
        var $file_quota;
        var $template_id;
        var $firstname;
        var $lastname;
        var $user_type;
        var $owner;
        var $blogs;
        var $folders;

        var $exists;
        
        /**
         *
         */
        function User($var)
        {
            $this->exists = false;

            // Both username or userid may be passed
            if (is_numeric($var))
            {
                // Numeric, we probably received a userid
                $info = db_query("select * from users where ident = '$var'");
            }
            elseif(is_string($var))
            {
                // String, we probably recieved a username
                $info = db_query("select * from users where username = '$var'");
            }

            if (sizeof($info) > 0)
            {
                $this->exists = true;

                $this->ident           = $info[0]->ident;
                $this->username        = $info[0]->username;
                $this->email           = $info[0]->email;
                $this->name            = $info[0]->name;
                $this->alias           = $info[0]->alias;
                $this->code            = $info[0]->code;
                $this->icon_quota      = $info[0]->icon_quota;
                $this->file_quota      = $info[0]->file_quota;
                $this->user_type       = $info[0]->user_type;
                $this->owner           = $info[0]->owner;
                $this->firstname       = preg_replace('/\s.*$/', '', $name);
                $this->lastname        = preg_replace('/^.*\s/', '', $name);
                
                // Load the weblog id's, starting with communities
                $communities = db_query("select users.ident from friends 
                                         left join users on users.ident = friends.friend 
                                         where friends.owner = $this->ident 
                                         and users.user_type = 'community' 
                                         group by friends.friend");

                $this->blogs = array();

                // Add the own weblog id (is same as user id)
                $this->blogs[] = $this->ident;

                // Add the communities
                foreach($communities as $community)
                {
                    $this->blogs[] = $community->ident;
                }
            }
        }

        /**
         *
         */
        function exists()
        {
            return $this->exists;
        }

        /**
         *
         */
        function getUserName()
        {
            return $this->username;
        }

        /**
         *
         */
        function getEmail()
        {
            return $this->email;
        }

        /**
         *
         */
        function getName()
        {
            return $this->name;
        }

        /**
         *
         */
        function getAlias()
        {
            return $this->alias;
        }

        /**
         *
         */
        function getCode()
        {
            return $this->code;
        }

        /**
         *
         */
        function getIconQuota()
        {
            return $this->icon_quota;
        }

        /**
         *
         */
        function getFileQuota()
        {
            return $this->file_quota;
        }

        /**
         *
         */
        function getTemplateId()
        {
            return $this->template_id;
        }

        /**
         *
         */
        function getUserType()
        {
            return $this->user_type;
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
        function getFirstName()
        {
            return $this->firstname;
        }

        /**
         *
         */
        function getLastName()
        {
            return $this->lastname;
        }

        /**
         *
         */
        function getPersonalUrl()
        {
            $url = url . $this->username . "/";
            return $url;
        }

        /**
         *
         */
        function getBlogs()
        {
            return $this->blogs;
        }

        /**
         *
         */
        function getFolders()
        {
            if (isset($this->folders))
            {
                // A bit awkward, but create a list of folder id's. Needed for the xml-rpc
                // code to determine a default upload folder
                $folders = db_query("select ident from file_folders 
                                     where files_owner = $this->ident");

                $this->folders = array();

                // Add the folders
                foreach($folders as $folder)
                {
                    $this->folders[] = $folder->ident;
                }
            }

            return $this->folders;
        }

        /**
         * Get a folder id by name
         *
         * Utility function. Needed by the xml-rpc code for determining 
         * the default upload folder which will be referenced by name.
         */
        function getFolderId($name)
        {
            $id = "";

            $folder = db_query("select from file_folders 
                                where name = '$name' 
                                and files_owner = $this->ident");

            // Return the first match, if available
            if (sizeof($folder) > 0)
            {
                $id = $folder[0]->ident;

                return $id;
            }
            else
            {
                return $id;
            }
        }

        /**
         *
         */
        function getFriends($limit = null)
        {
            // Unlimited if not passed or 0/empty
            if ($limit == null || $limit == 0 || $limit == "")
            {
                $inject_limit = "";
            }
            else
            {
                $inject_limit = " limit $limit";
            }

            $result = db_query("select friends.friend as user_id from friends 
                                left join users on users.ident = friends.friend 
                                where friends.owner = $this->ident and
                                users.user_type = 'person'
                                $inject_limit");
            $friends = array();

            foreach ($result as $friend)
            {
                $friends[] = $friend->user_id;
            }

            return $friends;
        }

        /**
         *
         */
        function getFriendOf($limit = null)
        {
            // Unlimited if not passed or 0/empty
            if ($limit == null || $limit == 0 || $limit == "")
            {
                $inject_limit = "";
            }
            else
            {
                $inject_limit = " limit $limit";
            }

            $result = db_query("select users.ident as user_id from friends 
                                 left join users on users.ident = friends.owner
                                 where friend = $this->ident and
                                 users.user_type = 'person'
                                 $inject_limit");

            $friend_of = array();

            foreach ($result as $named_by)
            {
                $friend_of[] = $named_by->ident;
            }

            return $friend_of;
        }

        /**
         *
         */
        function setUserName($val)
        {
            $this->username = addslashes($val);
        }

        /**
         *
         */
        function setEmail($val)
        {
            $this->email = addslashes($val);
        }

        /**
         *
         */
        function setName($val)
        {
            $this->name = addslashes($val);
        }

        /**
         *
         */
        function setAlias($val)
        {
            $this->alias = addslashes($val);
        }

        /**
         *
         */
        function setCode($val)
        {
            $this->code = $val;
        }

        /**
         *
         */
        function setIconQuota($val)
        {
            $this->icon_quota = $val;
        }

        /**
         *
         */
        function setFileQuota($val)
        {
            $this->file_quota = $val;
        }

        /**
         *
         */
        function setTemplateId($val)
        {
            $this->template_id = $val;
        }
    }
?>
