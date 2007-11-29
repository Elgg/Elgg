<?php

    Class User extends ElggObject
    {
        // Class variables have public access. With PHP5 and up they can be declared private.
        
        var $username;
        var $email;
        var $name;
        var $alias;
        var $code;
        var $icon;
        var $icon_quota;
        var $file_quota;
        var $template_id;
        var $firstname;
        var $lastname;
        var $user_type;
        var $blogs;
        var $folders;
        var $type = 'user';

        var $exists;
        
        /**
         *
         */
        function User($var)
        {
            global $CFG;
            $this->exists = false;

            // Both username or userid may be passed
            if (is_numeric($var))
            {
                // Numeric, we probably received a userid
                $info = get_record('users','ident',$var);
            }
            elseif(is_string($var))
            {
                // String, we probably recieved a username
                $info = get_record('users','username',$var);
            }

            if (!empty($info)) {
                $this->exists = true;

                $this->ident           = $info->ident;
                $this->username        = $info->username;
                $this->email           = $info->email;
                $this->name            = user_name($info->ident);
                $this->alias           = $info->alias;
                $this->code            = $info->code;
                $this->icon_quota      = $info->icon_quota;
                $this->file_quota      = $info->file_quota;
                $this->user_type       = $info->user_type;
                $this->owner           = $info->owner;

                ereg('^([a-zA-Z]*) (.*)', $this->name, $groups);
                $this->firstname       = trim($groups[1]);
                $this->lastname        = trim($groups[2]);
                
                // Load the weblog id's, starting with communities

                // Need to select two fields to collect instead of just u.ident else
                // this very handy datalib function will return false...
                $communities = get_records_sql('SELECT DISTINCT u.ident,u.name 
                                               FROM '.$CFG->prefix.'friends f
                                               JOIN '.$CFG->prefix.'users u 
                                               ON u.ident = f.friend
                                               WHERE f.owner = ? 
                                               AND u.user_type = ?',array($this->ident,'community'));

                $this->blogs = array();

                // Add the own weblog id (is same as user id)
                $this->blogs[] = $this->ident;

                // Add the communities
                if ($communities) {
                    foreach($communities as $community) {
                        $this->blogs[] = $community->ident;
                    }
                }

                $this->icon = user_icon_html($info->ident,100,true);
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
        function getUserIcon()
        {
            return $this->icon;
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
                $folders = get_records('file_folders','files_owner',$this->ident);

                $this->folders = array();

                // Add the folders
                if (is_array($folders)) {
                    foreach($folders as $folder) {
                        $this->folders[] = $folder->ident;
                    }
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
            global $CFG;
            $id = "";
            if ($folder = get_record_select('file_folders','name = ? AND files_owner = ?',array($name,$this->ident))) {
                return $folder->ident;
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
            global $CFG;
            // Unlimited if not passed or 0/empty
            if ($limit == null || $limit == 0 || $limit == "")
            {
                $inject_limit = "";
            }
            else
            {
                $inject_limit = " limit $limit";
            }
            $friends = array();

            if ($result = get_records_sql('SELECT f.friend AS user_id,u.name 
                                          FROM '.$CFG->prefix.'friends f 
                                          JOIN '.$CFG->prefix.'users u 
                                          ON u.ident = f.friend 
                                          WHERE f.owner = ? 
                                          AND u.user_type = ? '.$inject_limit, 
                                          array($this->ident,'person'))) {
                foreach ($result as $friend) {
                    $friends[] = $friend->user_id;
                }
            }
            
            return $friends;
        }

        /**
         *
         */
        function getFriendOf($limit = null)
        {
            global $CFG;
            // Unlimited if not passed or 0/empty
            if ($limit == null || $limit == 0 || $limit == "")
            {
                $inject_limit = "";
            }
            else
            {
                $inject_limit = " limit $limit";
            }


            $friend_of = array();

            if ($result = get_records_sql('SELECT u.ident AS user_id, u.name 
                                          FROM '.$CFG->prefix.'friends f 
                                          LEFT JOIN '.$CFG->prefix.'users u 
                                          ON u.ident = f.owner 
                                          WHERE f.friend = ? 
                                          AND u.user_type = ? '.$inject_limit,
                                          array($this->ident,'person'))) {
                foreach ($result as $named_by) {
                    $friend_of[] = $named_by->user_id;
                }
            }

            return $friend_of;
        }

        /**
         *
         */
        function setUserName($val)
        {
            $this->username = $val;
        }

        /**
         *
         */
        function setEmail($val)
        {
            $this->email = $val;
        }

        /**
         *
         */
        function setName($val)
        {
            $this->name = $val;
        }

        /**
         *
         */
        function setAlias($val)
        {
            $this->alias = $val;
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
