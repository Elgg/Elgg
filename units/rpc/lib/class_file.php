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

        var $exists;

        /**
         *
         */
        function File($var = 'default')
        {
            $this->exists;

            if ($var != "")
            {
                $file = db_query("select * from files where ident = $var");

                $this->ident         = $var;
                $this->owner         = $file[0]->owner;
                $this->files_owner   = $file[0]->files_owner;
                $this->folder        = $file[0]->folder;
                $this->community     = $file[0]->community;
                $this->title         = $file[0]->title;
                $this->originalname  = $file[0]->originalname;
                $this->description   = $file[0]->description;
                $this->location      = $file[0]->location;
                $this->access        = $file[0]->access;
                $this->size          = $file[0]->size;
                $this->time_uploaded = $file[0]->time_uploaded;
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
            $this->title = addslashes($val);
        }

        /**
         *
         */
        function setOriginalName($val)
        {
            $this->originalname = addslashes($val);
        }

        /**
         *
         */
        function setDescription($val)
        {
            $this->description = addslashes($val);
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
            $quotum_used = db_query("select sum(size) as sum from files where owner = $this->owner");
            $quotum_used = $quotum_used[0]->sum;

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
            $folder = run('folders:instance', array('id' => $this->folder));

            // Decode the file and store it
            $filename = time() . "_" . preg_replace("/[^\w.-]/i","_",$this->originalname);
            $storage  = $folder->getPersonalStorage();
        
            $if = fopen($storage. $filename, "wb");

            $file = base64_decode($data);

            fwrite($if, $file);
        
            fclose($if);

            $size = filesize($storage.$filename);

            $allowed = $this->checkQuota($size);

            if ($allowed == false)
            {
                // Quotum exceeded, remove the upload
                @unlink($storage.$filename);

                $result['value'] = false;
                $result['message'] = "Quotum exceeded";

                return $result;
            }
            else
            {
                $this->date = time();
                $this->size = $size;
                $this->location = $storage.$filename;

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
                db_query("insert into files set owner = $this->owner,
                          files_owner = $this->files_owner,
                          folder = $this->folder,
                          originalname = '$this->originalname',
                          title = '$this->title',
                          description = 'Automated upload',
                          location = '$this->location',
                          access = '$this->access',
                          size = '$this->size',
                          time_uploaded = ".time());

                if (db_affected_rows() > 0)
                {
                    $this->exists = true;
                    $this->ident = db_id();

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
                db_query("update files set 
                          folder = $this->folder,
                          title = '$this->title',
                          description = 'Automated upload',
                          access = '$this->access',
                          where ident = $this->ident");

                if (db_affected_rows() > 0)
                {
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
