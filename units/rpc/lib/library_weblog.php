<?php

    /*
     *  Functions related to weblogs
     */

    // Should be reorganised, perhaps class based. All functions have been added here 
        
    // Return userinfo
    function getElggUserInfo($username)
    {        
        $info = db_query("select * from users where username = '$username'");
        
        return $info;
    }
        
    // Return the firstname
    function getElggFirstName($name)
    {
        return preg_replace('/\s.*$/', '', $name);
    }
    
    
    // Return the lastname
    // TODO: dive into regexps again and fix this one
    function getElggLastName($name)
    {
        return preg_replace('/^.*\s/', '', $name);
    }

    
    // Return personal url
    function getElggPersonalUrl($username)
    {
        $url = url . $username . "/";
        
        return $url;
    }
    
    // Return weblog url
    function getElggPersonalWeblog($username)
    {
        $url = url . $username . "/weblog/";
        
        return $url;
    }
    
    // Return an array of structs of recent posts
    function getElggPosts($username, $number_of_posts)
    {
        $user_id = userNameToId($username);
        $number = (int) $number_of_posts;
        
        $posts = db_query("select * from weblog_posts where owner = $user_id order by posted desc limit $number");
        
        return $posts;
    }
    
    // Return a single post
    function getElggPost($postid)
    {
        $post_id = (int) $postid;
        
        $post = db_query("select * from weblog_posts where ident = $post_id");
        
        return $post;
    }
    
    // Save a new post, return the post id
    function saveElggPost($title, $content, $access, $username)
    {
        $user_id = (int) userNameToId($username);
        
        db_query("insert into weblog_posts set title = '$title', 
                  body = '$content', access = '$access', 
                  posted = ".time().", owner = $user_id");

        $post_id = "";

        if (db_affected_rows() > 0)
        {
            // Succes, return the id
            $post_id = db_id();
        }

        return $post_id;
    }
    
    // Save an existing post, return a boolean
    function editElggPost($postid, $title, $content, $access)
    {
        $post_id = (int) $postid;
        $result = false;
        
        db_query("update weblog_posts set title = '$title', 
                  body = '$content', access = '$access', 
                  posted = ".time()." where ident = $post_id");

        if (db_affected_rows() > 0)
        {
            // Succes, set the boolean
            $result = true;
        }

        return $esult;
    }
    
    // Delete a post, return boolean
    function deleteElggPost($postid)
    {
        $id = (int) $postid;
        $result = false;
        
        // Delete tags
        db_query("delete from tags where tagtype = 'weblog' and ref = $id");
        // Delete additional comments
        db_query("delete from weblog_comments where post_id = $id");
        // Delete entry
        db_query("delete from weblog_posts where ident = $id");

        if (db_affected_rows() > 0)
        {
            // Succes
            $return = true;
        }

        return $result;
    }
    
    // See if the default upload folder exists, else create; returns a boolean
    // really should be something like a generic createFolder which will gracefully return a true if it already exists
    function checkUploadFolder($username)
    {
        $user_id = userNameToId($username);
        
        $result = db_query("select ident from file_folders 
                            where parent = -1 
                            and name = 'weblog storage' 
                            and owner = $user_id");
        $folder_id = "";
                 
        if (sizeof($result) > 0)
        {
            $folder_id = $result[0]->ident;

            return $folder_id;
        }
        else
        {
            // Bummer, we need to create the folder
            db_query("insert into file_folders
                      set parent = -1,
                      name = 'weblog storage',
                      access = 'PUBLIC',
                      owner = $user_id");
            if (db_affected_rows() > 0)
            {
                $folder_id = db_id();
                
                // Create the folder
                $upload_folder = substr($username,0,1);
            
                if (!file_exists(path . "_files/data/" . $upload_folder))
                {
                    mkdir(path . "_files/data/" . $upload_folder);
                }

                if (!file_exists(path . "_files/data/" . $upload_folder . "/" . $username))
                {
                    mkdir(path . "_files/data/" . $upload_folder . "/" . $username);
                }

                return $folder_id;
            }
            else
            {
                // Return the empty string
                return $folder_id;
            }

        }
    }
    
    // Save the file
    function saveFile($username, $bits, $name)
    {
        $result = array();
        $result['code']    = null;
        $result['message'] = "";
        $result['url']     = "";
        $result['stored']  = false;

        $user_id = (int) userNameToId($username);

        // Setup the storage area
        $folder_id = checkUploadFolder($username);

        // Abort if the storage area is inaccessible
        if ($folder_id =="")
        {
            $result['code'] = -32500;
            $result['stored'] = false;
            $result['message'] = "Unable to locate the default storage area";
            
            return $result;        
        }
        
        // Decode the file and store it
        $new_filename = time() . "_" . preg_replace("/[^\w.-]/i","_",$name);
        $storage_dir = path . "_files/data/" . substr($username,0,1) . "/" . $username . "/";
        
        $ifp = fopen($storage_dir.$new_filename, "wb");

        $file = base64_decode($bits);

        fwrite( $ifp, $file);
        
        fclose( $ifp );

        
        // TODO Determine file size
        $file_size = filesize($storage_dir.$new_filename);
        
        // Check for quota
        $total_quota = db_query("select sum(size) as sum from files where owner = $user_id");
        $total_quota = $total_quota[0]->sum;

        $max_quota = db_query("select file_quota from users where ident = $user_id");
        $max_quota = $max_quota[0]->file_quota;

        if ($total_quota + $file_size > $max_quota)
        {
            $result['code'] = -32500;
            $result['stored'] = false;
            $result['message'] = "File quota exceeded";
            
            // Remove the upload
            @unlink($storage_dir.$new_filename);
            
            return $result;
        }
        else
        {

            db_query("insert into files set owner = ".$user_id.",
                      folder = $folder_id,
                      originalname = '$name',
                      title = '$name',
                      description = 'Automatic upload',
                      location = '".$storage_dir.$new_filename."',
                      access = 'PUBLIC',
                      size = '$file_size',
                      time_uploaded = ".time());

            $file_id = db_id();
            
            $result['code'] = 200;
            $result['stored'] = true;
            $result['message'] = "File has been stored";

            $result['url'] = url . $username . "/files/" . $folder_id . "/" . $file_id . "/" . $name; 
            return $result;
        }
    }
    
    /* Sketches for a class based structure */
    
    Class Folder
    {
        var $folder_id;
        var $parent_id;
        var $owner;
        var $name;
        var $access;
        var $exists;

        function Folder()
        {
        }

        function create()
        {
        }

        function delete()
        {
        }

        function save()
        {
        }

        function getId()
        {
        }

        function getParentId()
        {
        }

        function setParentId()
        {
        }

        function getName()
        {
        }

        function setName()
        {
        }

        function getOwner()
        {
        }

        function setOwner()
        {
        }

        function getAccess()
        {
        }

        function setAccess()
        {
        }

        function _getData()
        {
        }
        
        function commit()
        {
        }
        
        function rollback()
        {
        }
    }
    
    Class File
    {
        function File()
        {
        }
    }
    
    Class User
    {
        function User()
        {
        }
    }
?>
