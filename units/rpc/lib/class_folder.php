<?php
    Class Folder extends ElggObject
    {
        var $parent;
        var $child_folders;
        var $child_files;
        var $owner;
        var $files_owner;
        var $name;
        var $tags;
        var $access;

        var $username;
        var $exists;

        /**
         *
         */
        function Folder($var = "default")
        {
            $this->exists = false;

            if ($var != "")
            {
                if (is_numeric($var))
                {
                    $folder= db_query("select * from file_folders where ident = '$var'");
                }
                else
                {
                    $folder= db_query("select * from file_folders where name = '$var'");
                }

                $this->ident       = $folder[0]->ident;
                $this->owner       = $folder[0]->owner;
                $this->files_owner = $folder[0]->files_owner;
                $this->parent      = $folder[0]->parent;
                $this->name        = $folder[0]->name;
                $this->access      = $folder[0]->access;

                // Does the requested id exist
                if (sizeof($folder[0]) > 0)
                {
                    $this->exists = true;
                }

                $folder_childs = db_query("select * from file_folders where parent = '$var'");

                foreach ($folder_childs as $child)
                {
                    $this->child_folders[] = $child->ident;
                }

                $file_childs = db_query("select * from files where parent = '$var'");

                foreach ($file_childs as $child)
                {
                    $this->child_files[] = $child->ident;
                }

                $folder_tags = db_query("select ident from tags where ref = '$var'");

                // An aray of Tag objects
                foreach ($post_tags as $tag)
                {
                    $this->tags[] = $tag->ident;
                }
            }
        }

        // Utility function
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
        function getPersonalStorage()
        {
            // User's personal filesystem storage location
            $user = run('users:instance', array('user_id' => $this->files_owner));
            $this->username = $user->getUserName();

            $upload_folder = substr($this->username,0,1);

            return path . "_files/data/" . $upload_folder . "/" . $this->username . "/";
        }

        /**
         *
         */
        function setupPersonalStorage()
        {
            // Finally, create the default user filesystem folder, if not available
            $user = run('users:instance', array('user_id' => $this->owner));
            $this->username = $user->getUserName();
                
            $upload_folder = substr($this->username,0,1);
            
            if (!file_exists(path . "_files/data/" . $upload_folder))
            {
               mkdir(path . "_files/data/" . $upload_folder);
            }

            if (!file_exists(path . "_files/data/" . $upload_folder . "/" . $this->username))
            {
                mkdir(path . "_files/data/" . $upload_folder . "/" . $this->username);
            }
        }

        /**
         *
         */
        function getParent()
        {
            return $this->parent;
        }

        function getChildFolders()
        {
            return $this->child_folders;
        }

        /**
         *
         */
        function getChildFiles()
        {
            return $this->child_files;
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
        function getFilesOwner()
        {
            return $this->files_owner;
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
        function getAccess()
        {
            return $this->access;
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
        function setFilesOwner($val)
        {
            $this->files_owner = $val;
        }

        /**
         *
         */
        function setParent($val)
        {
            $this->parent = $val;
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
        function setAccess($val = "PUBLIC")
        {
            $this->access = $val;
        }

        /**
         *
         */
        function save()
        {
            $this->setupPersonalStorage();

            if ($this->exists == true)
            {
                // Owner is still unmutable
                db_query("update file_folders 
                          set parent = $this->parent,
                          name = '$this->name',
                          access = '$this->access'");

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
                db_query("insert into file_folders 
                          set parent = $this->parent,
                          name = '$this->name',
                          access = '$this->access',
                          owner = $this->owner,
                          files_owner = $this->files_owner");

                if (db_affected_rows() > 0)
                {
                    // Set the new folder id
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

        /**
         *
         */
        function delete()
        {
            // TODO refactor to handle more sophisticated operations like cut, paste, move

            // Also delete all subfolders and files
            db_query("delete from file_folders where ident = $this->ident");

            // Delete tags
            $this->deleteTags();

            // Reassign the parent id, default Elgg action when parent folder is being deleted
            foreach ($this->child_folders as $child)
            {
                $folder = run('folders:instance', array('id' => $child));
                $folder->setParent($this->parent);
                $folder->save();
            }

            // Reassign the parent id, default Elgg action when parent folder is being deleted
            foreach ($this->child_files as $child)
            {
                $folder = run('filess:instance', array('id' => $child));
                $folder->setParent($this->parent);
                $folder->save();
            }


            if (db_affected_rows > 0 )
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
