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
        var $type = 'collection';

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
                    $folder = get_record('file_folders','ident',$var);
                }
                else
                {
                    $folder = get_record('file_folders','name',$var);
                }

                if (!empty($folder)) {
                    $this->ident       = $folder->ident;
                    $this->owner       = $folder->owner;
                    $this->files_owner = $folder->files_owner;
                    $this->parent      = $folder->parent;
                    $this->name        = $folder->name;
                    $this->access      = $folder->access;
                    
                    $this->exists = true;
                }

                if ($folder_childs = get_records('file_folders','parent',$var)) {
                    foreach ($folder_childs as $child) {
                        $this->child_folders[] = $child->ident;
                    }
                }
                
                if ($file_childs = get_records('files','parent',$var)) {
                    foreach ($file_childs as $child) {
                        $this->child_files[] = $child->ident;
                    }
                }

                if ($folder_tags = get_records('tags','tagtype','folder','ref',$var)) {
                    // An aray of Tag objects
                    foreach ($folder_tags as $tag) {
                        $this->tags[] = $tag->ident;
                    }
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
         * Returns path without leading $CFG->dataroot
         */
        function getPersonalStorage()
        {
            $textlib  = textlib_get_instance();
            // User's personal filesystem storage location
            $user = run('users:instance', array('user_id' => $this->files_owner));
            $this->username = $user->getUserName();

            $personal_folder = 'files/' . $textlib->substr($this->username,0,1) . "/" . $this->username . "/";

            return $personal_folder;
        }

        /**
         *
         */
        function setupPersonalStorage()
        {
            global $CFG;

            $textlib = textlib_get_instance();
            // Finally, create the default user filesystem folder, if not available
            $user = run('users:instance', array('user_id' => $this->owner));
            $this->username = $user->getUserName();
                
            $base_folder = $CFG->dataroot . 'files/' . $textlib->substr($this->username,0,1);
            $personal_folder = $base_folder . "/" .$this->username;
            
            if (!file_exists($base_folder))
            {
               mkdir($base_folder);
            }

            if (!file_exists($personal_folder))
            {
                mkdir($personal_folder);
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
            $ff = new StdClass;
            $ff->parent = $this->parent;
            $ff->name = $this->name;
            $ff->access = $this->access;
            if ($this->exists == true)
            {
                // Owner is still unmutable
                $ff->ident = $this->ident;
                if (update_record('file_folders',$ff)) {
                    return $this->ident;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $ff->owner = $this->owner;
                $ff->files_owner = $this->files_owner;
                if ($this->ident = insert_record('file_folders',$ff)) {

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
            $deleted = delete_records('file_folders','ident',$this->ident);

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

            return $deleted;
        }
    }
?>
