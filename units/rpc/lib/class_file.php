<?php

    Class File extends ElggObject
    {
        var $owner;
        var $files_owner;
        var $folder;
        var $community;
        var $title;
        var $originalname;
        var $description;
        var $location;
        var $access;
        var $size;
        var $time_uploaded;
        var $type = 'file';

        var $exists;

        /**
         *
         */
        function File($var = 'default')
        {
            $this->exists;

            if ($var != "")
            {
                if ($file = get_record('files','ident',$var)) {
                    $this->ident         = $var;
                    $this->owner         = $file->owner;
                    $this->files_owner   = $file->files_owner;
                    $this->folder        = $file->folder;
                    $this->community     = $file->community;
                    $this->title         = $file->title;
                    $this->originalname  = $file->originalname;
                    $this->description   = $file->description;
                    $this->location      = $file->location;
                    $this->access        = $file->access;
                    $this->size          = $file->size;
                    $this->time_uploaded = $file->time_uploaded;
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
        function getUrl()
        {
            $user = run('users:instance', array('user_id' => $this->files_owner));
            
            return url . $user->getUserName() . "/files/" . $this->folder . "/" . $this->ident . "/" . $this->originalname; 
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
        function getFolder()
        {
            return $this->folder;
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
        function getTitle()
        {
            return $this->title;
        }

        /**
         *
         */
        function getOriginalName()
        {
            return $this->originalname;
        }

        /**
         *
         */
        function getDescription()
        {
            return $this-description;
        }

        /**
         *
         */
        function getLocation()
        {
            return $this->location;
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
        function getSize()
        {
            return $this->size;
        }
 
        /**
         *
         */
        function getDate()
        {
            return $this->time_uploaded;
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
        function setFolder($val)
        {
            $this->folder = $val;
        }

        /**
         *
         */
        function setCommunity($al)
        {
            $this->community = $val;
        }

        /**
         *
         */
        function setTitle($val)
        {
            $this->title = $val;
        }

        /**
         *
         */
        function setOriginalName($val)
        {
            $this->originalname = $val;
        }

        /**
         *
         */
        function setDescription($val)
        {
            $this->description = $val;
        }

        /**
         *
         */
        function setLocation($val)
        {
            $this->location = $val;
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
        function setSize($val)
        {
            $this->size = $val;
        }

        /**
         *
         */
        function setDate($val)
        {
            $this->date = $val;
        }

        /**
         *
         */
        function checkQuota($size)
        {
            // Get user quotum
            $user = run('users:instance', array('user_id' => $this->owner));
            
            $quotum = $user->getFileQuota();
            $quotum_used = get_field('files','sum(size)','owner',$this->owner);

            if ($quotum_used + $size > $quotum)
            {
                return false;
            }
            else
            {
                return true;
            }
        }

        // Store base64 encoded data
        /**
         *
         */
        function saveBase64Data($data)
        {
            global $CFG;

            $folder = run('folders:instance', array('id' => $this->folder));

            // Decode the file and store it
            $filename = time() . "_" . preg_replace("/[^\w.-]/i","_",$this->originalname);
            $storage  = $folder->getPersonalStorage();
        
            $if = fopen($CFG->dataroot . $storage . $filename, "wb");

            $file = base64_decode($data);

            fwrite($if, $file);
        
            fclose($if);

            $size = filesize($CFG->dataroot . $storage . $filename);

            $allowed = $this->checkQuota($size);

            if ($allowed == false)
            {
                // Quotum exceeded, remove the upload
                @unlink($CFG->dataroot . $storage . $filename);

                $result['value'] = false;
                $result['message'] = "Quotum exceeded";

                return $result;
            }
            else
            {
                $this->date = time();
                $this->size = $size;
                $this->location = $storage . $filename;

                return $this->save();
            }
        }

        /**
         *
         */
        function save()
        {
            if ($this->exists == false)
            {
                $f = new StdClass;
                $f->owner = $this->owner;
                $f->files_owner = $this->files_owner;
                $f->folder = $this->folder;
                $f->originalname = $this->originalname;
                $f->title = $this->title;
                $f->description = 'Automated upload';
                $f->location = $this->location;
                $f->access = $this->access;
                $f->size = $this->size;
                $f->time_uploaded =  time();
                $this->ident = insert_record('files',$f);
                if (!empty($this->ident)) {
                    $this->exists = true;

                    $result['value'] = $this->ident;
                    $result['message'] = "File stored";

                    return $result;
                }
                else
                {
                    $result['value'] = false;
                    $result['message'] = "File not stored";

                    return $result;
                }
            }
            else
            {
                $f = new StdClass;
                $f->folder = $this->folder;
                $f->title = $this->title;
                $f->description = 'Automated upload';
                $f->access = $this->access;
                $f->ident = $this->ident;
                if (update_record('files',$f)) {

                    $result['value'] = $this->ident;
                    $result['message'] = "File updated";

                    return $result;
                }
                else
                {
                    $result['value'] = false;
                    $result['message'] = "File not updated";

                    return $result;
                }
            }
        }
    }

?>
