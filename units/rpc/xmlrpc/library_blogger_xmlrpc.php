<?php

    /*
     * Blogger and metaWeblog API implementation
     */

    function generic_newPost($params, $method)
    {
        // Number of parameters
        $nr_params = null;            // Raise an XML-RPC error
        
        if ($method == "blogger.newPost")
        {
            $api = "blogger";
            $nr_params = 6;
        }
        elseif($method == "metaWeblog.newPost")
        {
            $api = "metaWeblog";
            $nr_params = 5;
        }
        
        // Do we have the required number of parameters?
        if (count($params) != $nr_params)
        {
            // Raise an XML-RPC error
            return new IXR_Error(-32602, "Invalid method parameters");
        }
        
        // Parse parameters
        if ($api == "blogger")
        {
            $blogid   = $params[1];
            $username = $params[2];
            $password = $params[3];
            $content  = $params[4];
            $publish  = (boolean) $params[5];
        }
        else
        {
            $blogid   = $params[0];
            $username = $params[1];
            $password = $params[2];
            $content  = $params[3];
            $publish  = (boolean) $params[4];
        }
       
        // Exit if no blogid provided
        if ($blogid == "")
        {
            // Raise an XML-RPC error
            return new IXR_Error(-32602, "No blog ID provided");
        }

        // Check credentials
        $auth = run('rpc:auth', array("username" => $username,
                                      "password" => $password));
        if ($auth['status'] == true)
        {
            $user = run('users:instance', array('user_id' => $username));
            $post = run('posts:instance');
            
            $post->setOwner($user->getIdent());
            $post->setWeblog($blogid);

            $title = "";
            
            // Access level, only public or private supported
            if ($publish == true)
            {
                $access = "PUBLIC";
            }
            else
            {
                $access = "user".$user->getIdent();
            }
            
            $post->setAccess($access);
            
            if ($api == "blogger")
            {
                $post->setTitle($title);
                $post->setBody($content);
            }
            else
            {
                // TODO possibly handle interesting mt extensions
                //   (String) mt_text_more (the value for the additional entry text, combine with {{cut}}?)
                //   (String) mt_excerpt (the value for the excerpt field)
                //   (Boolean) mt_allow_comments (Elgg only handles this as a global and not per post option)
                $post->setTitle($content['title']);
                $post->setBody($content['description']);
            }

            // Save first because we need a postid to be able to add the tags later
            $post->save();
            
            // We support mt_keywords if request is metaWeblog
            if ($api != "blogger" && array_key_exists('mt_keywords', $content) && $post->getIdent() != "")
            {
                $keywords =  $content['mt_keywords'];
                $tags = explode(',', $keywords);
                sort($tags);

                foreach ($tags as $tag_name)
                {
                    $tag = new Tag('tags:instance');
                    $tag->setOwner($user->getIdent());
                    $tag->setRef($post->getIdent()); // new post id should be available
                    $tag->setTagName($tag_name);
                    $tag->setAccess($access);
                    $tag->save();
                }
            }

            // Do we have a new post id?
            if ($post->getIdent() != "")
            {
                return $post->getIdent();
            }
            else
            {
                // No new post id available, item hasn't been saved so raise an XML-RPC error
                return new IXR_Error(-32500, "Unable to save item");
            }
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            return new IXR_Error($auth['code'], $auth['message']);
        }
    }
    
    function generic_editPost($params, $method)
    {
        // TODO editPost is almost similar to newPost, try to merge them
        
        // Number of parameters
        $nr_params = null;
        
        if ($method == "blogger.editPost")
        {
            $api = "blogger";
            $nr_params = 6;
        }
        elseif($method == "metaWeblog.editPost")
        {
            $api = "metaWeblog";
            $nr_params = 5;
        }
        
        // Do we have the required number of parameters?
        if (count($params) != $nr_params)
        {
            // Raise an XML-RPC error
            return new IXR_Error(-32602, "Invalid method parameters");
        }
        
        // Parse parameters
        if ($api == "blogger")
        {
            $postid   = $params[1];
            $username = $params[2];
            $password = $params[3];
            $content  = $params[4];
            $publish  = $params[5];
        }
        else
        {
            $postid   = $params[0];
            $username = $params[1];
            $password = $params[2];
            $content  = $params[3];
            $publish  = $params[4];
        }

        // Check credentials
        $auth = run('rpc:auth', array("username" => $username,
                                      "password" => $password));
       
        if ($auth['status'] == true)
        {
            $post = run('posts:instance', array('id' => $postid));
            $user = run('users:instance', array('user_id' => $username));

            // Access level, only public or private supported
            if ($publish == true)
            {
                $access = "PUBLIC";
            }
            else
            {
                $access = "user".$user->getIdent();
            }
            
            $post->setAccess($access);
            
            if ($api == "blogger")
            {
                $post->setTitle($title);
                $post->setBody($content);
            }
            else
            {
                // TODO possibly handle interesting mt extensions
                //   (String) mt_text_more (the value for the additional entry text, combine with {{cut}}?)
                //   (String) mt_excerpt (the value for the excerpt field)
                
                $post->setTitle($content['title']);
                $post->setBody($content['description']);

                // We support mt_keywords
                if (array_key_exists('mt_keywords', $content) && $result != false)
                {
                    // Delete existing tags first
                    $post->deleteTags();

                    // Crunch and set the keywords as tags
                    $keywords =  $content['mt_keywords'];
                    $tags = explode(',', $keywords);

                    foreach ($tags as $tag)
                    {
                        $tag = new Tag('tags:instance');
                        $tag->setOwner($user->getIdent());
                        $tag->setRef($post->getIdent());
                        $tag->setTagName($tag);
                        $tag->setAccess($access);
                        $tag->save();
                    }
                }
            }

            // Do we have a succesfull save?
            if ($post->save() == true)
            {
                return true;
            }
            else
            {
                // Modification are not saved, raise an XML-RPC error
                return new IXR_Error(-32500, "Unable to update item");
            }
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            return new IXR_Error($auth['code'], $auth['message']);
        }
    }
    
    /*
     */
    function blogger_deletePost($params, $method)
    {
        // Parse parameters
        $postid   = $params[1];
        $username = $params[2];
        $password = $params[3];
        $publish  = (boolean) $params[4]; // not used

        // Check credentials
        $auth = run('rpc:auth', array("username" => $username,
                                      "password" => $password));
       
        if ($auth['status'] == true)
        {
            $post = run('posts:instance', array('id' => $postid));
            
            if ($post->delete() == true)
            {
                return true;
            }
            else
            {
                // Message not deleted, raise an XML-RPC error
                return new IXR_Error(-32500, "Unable to delete post");
            }
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            return new IXR_Error($auth['code'], $auth['message']);
        }
    }
    
    /*
     * Description: Returns a list of the most recent posts in the system.
     *
     * Parameters: String appkey, String blogid, String username, String password, int numberOfPosts
     *
     * Return value: on success, array of structs containing ISO.8601 dateCreated, String userid, 
     * String postid, String content; on failure, fault
     *
     * Notes: dateCreated is in the timezone of the weblog; blogid and 
     * blogid parameters are not being used
     */
    function generic_getRecentPosts($params, $method)
    {
        // Number of parameters
        $nr_params = null;
        
        if ($method == "blogger.getRecentPosts")
        {
            $api = "blogger";
            $nr_params = 5;
        }
        elseif($method == "metaWeblog.getRecentPosts")
        {
            $api = "metaWeblog";
            $nr_params = 4;
        }
        
        // Do we have the required number of parameters?
        if (count($params) != $nr_params)
        {
            // Raise an XML-RPC error
            return new IXR_Error(-32602, "Invalid method parameters");
        }

        // Parse parameters
        if ($api == "blogger")
        {
            $blogid   = $params[1];
            $username = $params[2];
            $password = $params[3];
            $numberOfPosts  = (int) $params[4];
        }
        elseif($api == "metaWeblog")
        {
            $blogid   = $params[0];
            $username = $params[1];
            $password = $params[2];
            $numberOfPosts  = (int) $params[3];
        }

        // Check credentials
        $auth = run('rpc:auth', array("username" => $username,
                                      "password" => $password));

        // Load the user
        $user = run('users:instance', array('user_id' => $username));
        $user_id = $user->getIdent();

        if ($auth['status'] == true)
        {
            // Minimum of one, no maximum (for now)
            if ($numberOfPosts >= 1)
            {
                $weblog = run('weblogs:instance', array('user_id' => $user_id,
                                                        'blog_id' => $blogid));

                // Get the posts
                $posts  = array_slice($weblog->getPosts(), 0, $numberOfPosts);

                // Global results array
                $result = array();

                if (!empty($posts)) {

                    foreach($posts as $post_id)
                    {
                        $post = $weblog->getPost($post_id);
                        
                        // Local array to hold a single post
                        $entry = array();
                        
                        // Fill the post array, same for blogger and metaWeblog
                        $entry['dateCreated'] = new IXR_Date($post->getPosted());
                        $entry['userid']      = $weblog->getIdent(); // is username
                        $entry['postid']      = $post->getIdent();
                        
                        // Here the API's differ
                        if ($api == "blogger")
                        {
                            $entry['content']     = $post->getBody();
                        }
                        else
                        {
                            $entry['title']       = $post->getTitle();
                            $entry['description'] = $post->getBody();
                            $entry['url']         = $post->getUrl();
                            $entry['permalink']   = $post->getPermaLink();
                        }
 
                        // We support tags as mt_keywords
                        $tags = $post->getTags();

                        if (!empty($tags)) {

                            $keywords = array();

                            foreach($tags as $tag_id)
                            {
                                $tag = $post->getTag($tag_id);

                                $keywords[] = $tag->getTagName();
                            }
                            
                            $str_keywords = implode(',', $keywords);


                            // Add the keywords
                            $entry['mt_keywords'] = $str_keywords;
                        }

                        // Add it to the results array
                        $result[] = $entry;  
                    }

                    return $result;
                }
                else
                {
                    // Numbers of requested posts can't be provided, raise an XML-RPC error
                    return new IXR_Error(806, "No Such Item");
                }
            }
            else
            {
                // Wrong amount of requested posts, raise an XML-RPC error
                return new IXR_Error(805, "Amount parameter must be 1 or more");
            }
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            return new IXR_Error($auth['code'], $auth['message']);
        }
    }

    /*
     * Description: Returns a list of weblogs to which an author has posting privileges.
     *
     * Parameters: String appkey, String username, String password
     *
     * Return value: on success, array of structs containing String url, String blogid, 
     * String blogName; on failure, fault
     *
     * Notes: Currently elgg only provides one weblog per user; appkey is not being used
     */
    function blogger_getUsersBlogs($params, $method)
    {
        // Do we have the required number of parameters?
        if (count($params) != 3)
        {
            // Raise an XML-RPC error
            return new IXR_Error(-32602, "Invalid method parameters");
        }

        // Parse parameters
        $username = $params[1];
        $password = $params[2];

        // Check credentials
        $auth = run('rpc:auth', array("username" => $username,
                                      "password" => $password));
        
        if ($auth['status'] == true)
        {
            // Load the user
            $user = run('users:instance', array('user_id' => $username));
            $user_id = $user->getIdent();

            // Get the user's blogs
            $blogs = $user->getBlogs();

            // Result array
            $result = array();
            
            foreach ($blogs as $blog_id)
            {
                // Load the weblog
                $weblog = run('weblogs:instance', array('user_id' => $user_id,
                                                        'blog_id' => $blog_id));
            
                // Array to hold the weblog data
                $value = array();
            
                $value['url']      = $weblog->getUrl();
                $value['blogid']   = $weblog->getIdent();
                $value['blogName'] = $weblog->getTitle();

                // Add it to the result array
                $result[] = $value;
            }
            
            return $result;
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            return new IXR_Error($auth['code'], $auth['message']);
        }
    }
    
    /*
     * Description: Returns information about an author in the system.
     *
     * Parameters: String appkey, String username, String password
     *
     * Return value: on success, struct containing String userid, String firstname, 
     * String lastname, String nickname, String email, String url; on failure, fault
     *
     * Notes: firstname is the Elgg username up to the first space character, and lastname 
     * is the username after the first space character. nickname is elgg username. 
     * appkey is not being used.
     */
    function blogger_getUserInfo($params, $method)
    {
        // Do we have the required number of parameters?
        if (count($params) != 3)
        {
            // Raise an XML-RPC error
            return new IXR_Error(-32602, "Invalid method parameters");
        }
        
        // Parse parameters
        $username = $params[1];
        $password = $params[2];
        
        // Check credentials
        $auth = run('rpc:auth', array("username" => $username,
                                      "password" => $password));
        
        if ($auth['status'] == true)
        {
            // Get user info
            $user   = run('users:instance', array("user_id" => $username));
            
            // Global result array
            $result = array();
            
            // Fill the results array
            $result['userid']    = $user->getUserName();
            $result['firstname'] = $user->getFirstName();
            $result['lastname']  = $user->getLastName();
            $result['nickname']  = $user->getUserName();
            $result['email']     = $user->getEmail();
            $result['url']       = $user->getPersonalUrl();

            return $result;
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            return new IXR_Error($auth['code'], $auth['message']);
        }
    }
    
    function metaweblog_getPost($params, $method)
    {
        // Do we have the required number of parameters?
        if (count($params) != 3)
        {
            // Raise an XML-RPC error
            return new IXR_Error(-32602, "Invalid method parameters");
        }
        
        // Parse parameters
        //$param    = $params->getParam(0);
        $postid   = $params[0];
        $username = $params[1];
        $password = $params[2];
        
        // Check credentials
        $auth = run('rpc:auth', array("username" => $username,
                                      "password" => $password));
        
        if ($auth['status'] == true)
        {
            $weblog = run('weblogs:instance', array('id' => $username));
            
            $post = $weblog->getPost($postid);
            
            // Global results array
            $entry = array();
                
            // Fill the post array
            $entry['dateCreated'] = new IXR_Date($post->getPosted());
            $entry['userid']      = $username;
            $entry['postid']      = $postid;
            $entry['title']       = $post->getTitle();
            $entry['description'] = $post->getBody();
            $entry['url']         = $post->getUrl();
            $entry['permalink']   = $post->getPermaLink();

            // Support mt_keywords
            $tags = $post->getTags();

            if (!empty($tags)) {

                $keywords = array();

                foreach($tags as $tag_id)
                {
                    $tag = $post->getTag($tag_id);

                    $keywords[] = $tag->getTagName();
                }
                            
                $str_keywords = implode(',', $keywords);

                // Add the keywords
                $entry['mt_keywords'] = $str_keywords;
            }

            return $entry;
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            return new IXR_Error($auth['code'], $auth['message']);
        }
    }

    /*
     * Description: Uploads a file to your webserver.
     *
     * Parameters: String blogid, String username, String password, struct file
     *
     * Return value: URL to the uploaded file.
     *
     * Notes: the struct file should contain two keys: base64 bits (the base64-encoded contents of 
     * the file) and String name (the name of the file). The type key (media type of the file) is 
     * currently ignored.
     */
    function metaweblog_newMediaObject($params, $method)
    {
        // Do we have the required number of parameters?
        if (count($params) != 4)
        {
            // Raise an XML-RPC error
            return new IXR_Error(0, -32602, "Invalid method parameters");
        }
        
        // Parse parameters

        $blogid   = $params[0];
        $username = $params[1];
        $password = $params[2];
        $file     = $params[3];
        
        // Check credentials
        $auth = run('rpc:auth', array("username" => $username,
                                      "password" => $password));
        
        if ($auth['status'] == true)
        {
            // Get the file type and contents
            $type = $file['type'];
            $bits = $file['bits'];
            $name = $file['name'];
            
            // New user object
            $user = run('users:instance', array('user_id' => $username));
            
            // New file object
            $file = run('files:instance');

            // Get the upload folder id
            $storage_id = $user->getFolderId("Weblog storage");

            // Open or create the default upload folder
            if ($storage_id == "")
            {
                $folder = run('folders:instance');

                $folder->setOwner($user->getIdent());
                $folder->setFilesOwner($blogid);
                $folder->setName('Weblog storage');
                $folder->setParent(-1);
                $folder->setAccess('PUBLIC');
                $folder->save();
            }
            else
            {
                $folder = run('folders:instance', array('id' => $storage_id));
            }

            // Prepare the file
            $file->setOwner($user->getIdent());
            $file->setFilesOwner($blogid);
            $file->setAccess('PUBLIC'); // No access information available, assume public
            $file->setFolder($folder->getIdent());
            $file->setOriginalName($name);

            $stored = $file->saveBase64Data($bits);

            // A file id is returned, if all is well
            if (is_numeric($stored['value']))
            {
                // TODO the MT spec defines the return type as a string, 
                // the metaWeblog spec as a struct with key 'url'. 
                // Find out which one (most) clients use. For now use the struct

                // Return the URL as a struct
                $uri['url'] = $file->getUrl();
                                
                return $uri;
            }
            else
            {
                // No url, raise an XML-RPC error
                return new IXR_Error(-2, $stored['message']);
            }
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            return new IXR_Error($auth['code'], $auth['message']);
        }
    }
?>
