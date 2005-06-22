<?php

    /*
     * Blogger and metaWeblog API implementation
     */

    // Prepare a list of handlers to be loaded into the XML-RPC server
    
    $handlers_blogger = array('blogger.newPost'           => array('function' => 'genric_newPost'),
                              'blogger.editPost'          => array('function' => 'generc_editPost'),
                              'blogger.deletePost'        => array('function' => 'blogger_deletePost'),
                              'blogger.getRecentPosts'    => array('function' => 'generic_getRecentPosts'),
                              'blogger.getUsersBlogs'     => array('function' => 'blogger_getUsersBlogs'),
                              'blogger.getUserInfo'       => array('function' => 'blogger_getUserInfo'),
                              'metaWeblog.newPost'        => array('function' => 'generic_newPost'),
                              'metaWeblog.getRecentPosts' => array('function' => 'generic_getRecentPosts'),
                              'metaWeblog.editPost'       => array('function' => 'generic_editPost'),
                              'metaWeblog.getPost'        => array('function' => 'metaweblog_getPost'),
                              'metaWeblog.newMediaObject' => array('function' => 'metaweblog_newMediaObject'));

    // Add the handlers to the global handlers array
    $handlers = $handlers + $handlers_blogger;

    function generic_newPost($params)
    {
        // How are we being called?
        $method    = $params->method();
        
        // Number of parameters
        $nr_params = null;
        
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
        if ($params->getNumParams() != $nr_params)
        {
            // Raise an XML-RPC error
            $response = new XML_RPC_Response (0, -32602, "Invalid method parameters");
            
            return $response;
        }
        
        // Parse parameters
        if ($api == "blogger")
        {
            $param    = $params->getParam(1);
            $blogid   = $param->scalarval();

            $param    = $params->getParam(2);
            $username = $param->scalarval();
        
            $param    = $params->getParam(3);
            $password = $param->scalarval();
        
            $param    = $params->getParam(4);
            $content  = $param->scalarval();
        
            $param    = $params->getParam(5);
            $publish  = (boolean) $param->scalarval();
        }
        else
        {
            $param    = $params->getParam(0);
            $blogid = $param->scalarval();

            $param    = $params->getParam(1);
            $username = $param->scalarval();
        
            $param    = $params->getParam(2);
            $password = $param->scalarval();
        
            $param    = $params->getParam(3);
            $content  = $param->scalarval();
        
            $param    = $params->getParam(4);
            $publish  = (boolean) $param->scalarval();
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
                
                $post->setTitle($content['title']->scalarval());
                $post->setBody($content['description']->scalarval());               
            }

            // Save first because we need a postid to be able to add the tags later
            $post->save();
            
            // We support mt_keywords
            if (array_key_exists('mt_keywords', $content) && $post->getIdent() != "")
            {
                $keywords =  $content['mt_keywords']->scalarval();
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
                $value = new XML_RPC_Value($post->getIdent());
                $response = new XML_RPC_Response($value);

                return $response;
            }
            else
            {
                // No new post id available, item hasn't been saved so raise an XML-RPC error
                $response = new XML_RPC_Response (0, -32500, "Unable to save item");

                return $response;
            }
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            $response = new XML_RPC_Response (0, $auth['code'], $auth['message']);
            
            return $response;
        }
    }
    
    function generic_editPost($params)
    {
        // TODO editPost is almost similar to newPost, try to merge them

        // How are we being called?
        $method    = $params->method();
        
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
        if ($params->getNumParams() != $nr_params)
        {
            // Raise an XML-RPC error
            $response = new XML_RPC_Response (0, -32602, "Invalid method parameters");
            
            return $response;
        }
        
        // Parse parameters
        if ($api == "blogger")
        {
            $param    = $params->getParam(1);
            $postid   = $param->scalarval();

            $param    = $params->getParam(2);
            $username = $param->scalarval();

            $param    = $params->getParam(3);
            $password = $param->scalarval();

            $param    = $params->getParam(4);
            $content  = $param->scalarval();

            $param    = $params->getParam(5);
            $publish  = $param->scalarval();
        }
        else
        {
            $param    = $params->getParam(0);
            $postid   = $param->scalarval();

            $param    = $params->getParam(1);
            $username = $param->scalarval();

            $param    = $params->getParam(2);
            $password = $param->scalarval();

            $param    = $params->getParam(3);
            $content  = $param->scalarval();

            $param    = $params->getParam(4);
            $publish  = $param->scalarval();
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
                
                $post->setTitle($content['title']->scalarval());
                $post->setBody($content['description']->scalarval());

                // We support mt_keywords
                if (array_key_exists('mt_keywords', $content) && $result != false)
                {
                    // Delete existing tags first
                    $post->deleteTags();

                    // Crunch and set the keywords as tags
                    $keywords =  $content['mt_keywords']->scalarval();
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
                $value = new XML_RPC_Value($result, 'boolean');
                $response = new XML_RPC_Response($value);

                return $response;
            }
            else
            {
                // Modification are not saved, raise an XML-RPC error
                $response = new XML_RPC_Response (0, -32500, "Unable to update item");

                return $response;
            }
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            $response = new XML_RPC_Response (0, $auth['code'], $auth['message']);
            
            return $response;
        }
    }
    
    /*
     */
    function blogger_deletePost($params)
    {
        // Parse parameters
        $param    = $params->getParam(1);
        $postid   = $param->scalarval();

        $param    = $params->getParam(2);
        $username = $param->scalarval();
        
        $param    = $params->getParam(3);
        $password = $param->scalarval();
        
        $param    = $params->getParam(4);
        $publish  = (boolean) $param->scalarval(); // not used

        // Check credentials
        $auth = run('rpc:auth', array("username" => $username,
                                      "password" => $password));
       
        if ($auth['status'] == true)
        {
            $post = run('posts:instance', array('id' => $postid));
            
            if ($post->delete() == true)
            {
                $value = new XML_RPC_Value(true, 'boolean');

                $response = new XML_RPC_Response($value);

                return $response;
            }
            else
            {
                // Message not deleted, raise an XML-RPC error
                $response = new XML_RPC_Response (0, -32500, "Unable to delete post");
            
                return $response;
            }
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            $response = new XML_RPC_Response (0, $auth['code'], $auth['message']);
            
            return $response;
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
     * Notes: dateCreated is in the timezone of the weblog; blogid and blogid parameters are 
     * not being used
     */
    function generic_getRecentPosts($params)
    {
        // How are we being called?
        $method    = $params->method();
        
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
        if ($params->getNumParams() != $nr_params)
        {
            // Raise an XML-RPC error
            $response = new XML_RPC_Response (0, -32602, "Invalid method parameters");
            
            return $response;
        }

        // Parse parameters
        if ($api == "blogger")
        {
            $param    = $params->getParam(1);
            $blogid   = $param->scalarval();
            
            $param    = $params->getParam(2);
            $username = $param->scalarval();

            $param    = $params->getParam(3);
            $password = $param->scalarval();

            $param          = $params->getParam(4);
            $numberOfPosts  = (int) $param->scalarval();
        }
        elseif($api == "metaWeblog")
        {
            $param    = $params->getParam(0);
            $blogid   = $param->scalarval();

            $param    = $params->getParam(1);
            $username = $param->scalarval();

            $param    = $params->getParam(2);
            $password = $param->scalarval();

            $param          = $params->getParam(3);
            $numberOfPosts  = (int) $param->scalarval();
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
            {$username = 5;
                $weblog = run('weblogs:instance', array('user_id' => $user_id,
                                                        'blog_id' => $blogid));
//$final = new XML_RPC_Value($weblog->user_id, 'string');
//$response = new XML_RPC_Response($final);
//return $response;

                // Get the posts
                $posts  = array_slice($weblog->getPosts(), 0, $numberOfPosts);
                
                // Global results array
                $result = array();

                if (sizeof($posts) > 0)
                {
                    foreach($posts as $post_id)
                    {
                        $post = $weblog->getPost($post_id);
                        
                        // Local array to hold a single post
                        $entry = array();
                        
                        // Fill the post array, same for blogger and metaWeblog
                        $entry['dateCreated'] = new XML_RPC_Value(date('Ymd\TH:i:s', $post->getPosted()), 'dateTime.iso8601');
                        $entry['userid']      = new XML_RPC_Value($weblog->getIdent()); // is username
                        $entry['postid']      = new XML_RPC_Value($post->getIdent());
                        
                        // Here the API's differ
                        if ($api == "blogger")
                        {
                            $entry['content']     = new XML_RPC_Value($post->getBody());
                        }
                        else
                        {
                            $entry['title']       = new XML_RPC_Value($post->getTitle());
                            $entry['description'] = new XML_RPC_Value($post->getBody());
                            $entry['url']         = new XML_RPC_Value($post->getUrl());
                            $entry['permalink']   = new XML_RPC_Value($post->getPermaLink());
                        }
                                          $tags = $post->getTags();

                        if (sizeof($tags) > 0)
                        {
                            $keywords = array();

                            foreach($tags as $tag_id)
                            {
                                $tag = $post->getTag($tag_id);

                                $keywords[] = $tag->getTagName();
                            }
                            
                            $str_keywords = implode(',', $keywords);


                            // Add the keywords
                            $entry['mt_keywords'] = new XML_RPC_Value($str_keywords);
                        }
 
                        // We support tags as mt_keywords
                        $tags = $post->getTags();

                        if (sizeof($tags) > 0)
                        {
                            $keywords = array();

                            foreach($tags as $tag_id)
                            {
                                $tag = $post->getTag($tag_id);

                                $keywords[] = $tag->getTagName();
                            }
                            
                            $str_keywords = implode(',', $keywords);


                            // Add the keywords
                            $entry['mt_keywords'] = new XML_RPC_Value($str_keywords);
                        }
                        
                        
                        // Construct the encoded post struct
                        $value = new XML_RPC_Value($entry, 'struct');
                        
                        // Add it to the results array
                        $result[] = $value;  
                    }

                    // Construct the final encoded array
                    $final = new XML_RPC_Value($result, 'array');
                    
                    // Prepare the response
                    $response = new XML_RPC_Response($final);
            
                    return $response;
                }
                else
                {
                    // Numbers of requested posts can't be provided, raise an XML-RPC error
                    $response = new XML_RPC_Response(0, 806, "No Such Item");
            
                    return $response;
                }
            }
            else
            {
                // Wrong amount of requested posts, raise an XML-RPC error
                $response = new XML_RPC_Response(0, 805, "Amount parameter must be 1 or more");
            
                return $response;
            }
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            $response = new XML_RPC_Response (0, $auth['code'], $auth['message']);
            
            return $response;
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
    function blogger_getUsersBlogs($params)
    {
        // Do we have the required number of parameters?
        if ($params->getNumParams() != 3)
        {
            // Raise an XML-RPC error
            $response = new XML_RPC_Response (0, -32602, "Invalid method parameters");
            
            return $response;
        }

        // Parse parameters
        $param    = $params->getParam(1);
        $username = $param->scalarval();
        
        $param    = $params->getParam(2);
        $password = $param->scalarval();

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
            
                $value['url']      = new XML_RPC_Value($weblog->getUrl());
                $value['blogid']   = new XML_RPC_Value($weblog->getIdent());
                $value['blogName'] = new XML_RPC_Value($weblog->getTitle());

                // Construct the encoded weblog struct
                $blog = new XML_RPC_Value($value, 'struct');
            
                // Add it to the result array
                $result[] = $blog;
            }
            
            // Construct the final encoded array
            $final = new XML_RPC_Value($result, 'array');

            // Prepare the response
            $response = new XML_RPC_Response($final);
            
            return $response;
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            $response = new XML_RPC_Response (0, $auth['code'], $auth['message']);
            
            return $response;
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
    function blogger_getUserInfo($params)
    {
        // Do we have the required number of parameters?
        if ($params->getNumParams() != 3)
        {
            // Raise an XML-RPC error
            $response = new XML_RPC_Response (0, -32602, "Invalid method parameters");
            
            return $response;
        }
        
        // Parse parameters
        $param    = $params->getParam(1);
        $username = $param->scalarval();
        
        $param    = $params->getParam(2);
        $password = $param->scalarval();
        
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
            $result['userid']    = new XML_RPC_Value($user->getUserName());
            $result['firstname'] = new XML_RPC_Value($user->getFirstName());
            $result['lastname']  = new XML_RPC_Value($user->getLastName());
            $result['nickname']  = new XML_RPC_Value($user->getUserName());
            $result['email']     = new XML_RPC_Value($user->getEmail());
            $result['url']       = new XML_RPC_Value($user->getPersonalUrl());

            // Construct the encoded info struct
            $value = new XML_RPC_Value($result, 'struct');
            
            // Prepare the response
            $response = new XML_RPC_Response($value);
            
            return $response;
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            $response = new XML_RPC_Response (0, $auth['code'], $auth['message']);
            
            return $response;
        }
    }
    
    function metaweblog_getPost($params)
    {
        // Do we have the required number of parameters?
        if ($params->getNumParams() != 3)
        {
            // Raise an XML-RPC error
            $response = new XML_RPC_Response (0, -32602, "Invalid method parameters");
            
            return $response;
        }
        
        // Parse parameters
        $param    = $params->getParam(0);
        $postid   = $param->scalarval();
        
        $param    = $params->getParam(1);
        $username = $param->scalarval();
        
        $param    = $params->getParam(2);
        $password = $param->scalarval();
        
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
            $entry['dateCreated'] = new XML_RPC_Value(date('Ymd\TH:i:s', $post->getPosted()), 'dateTime.iso8601');
            $entry['userid']      = new XML_RPC_Value($username);
            $entry['postid']      = new XML_RPC_Value($postid);
            $entry['title']       = new XML_RPC_Value($post->getTitle());
            $entry['description'] = new XML_RPC_Value($post->getBody());
            $entry['url']         = new XML_RPC_Value($post->getUrl());
            $entry['permalink']   = new XML_RPC_Value($post->getPermaLink());

            // Support mt_keywords
            $tags = $post->getTags();

            if (sizeof($tags) > 0)
            {
                $keywords = array();

                foreach($tags as $tag_id)
                {
                    $tag = $post->getTag($tag_id);

                    $keywords[] = $tag->getTagName();
                }
                            
                $str_keywords = implode(',', $keywords);

                // Add the keywords
                $entry['mt_keywords'] = new XML_RPC_Value($str_keywords);
            }

            // Construct the encoded info struct
            $value = new XML_RPC_Value($entry, 'struct');
            
            // Prepare the response
            $response = new XML_RPC_Response($value);

            return $response;
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            $response = new XML_RPC_Response (0, $auth['code'], $auth['message']);
            
            return $response;
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
    function metaweblog_newMediaObject($params)
    {
        // Do we have the required number of parameters?
        if ($params->getNumParams() != 4)
        {
            // Raise an XML-RPC error
            $response = new XML_RPC_Response (0, -32602, "Invalid method parameters");
            
            return $response;
        }
        
        // Parse parameters

        $param    = $params->getParam(0);
        $blogid   = $param->scalarval();
        
        $param    = $params->getParam(1);
        $username = $param->scalarval();

        $param    = $params->getParam(2);
        $password = $param->scalarval();

        $param    = $params->getParam(3);
        $file     = $param->scalarval();
        
        // Check credentials
        $auth = run('rpc:auth', array("username" => $username,
                                      "password" => $password));
        
        if ($auth['status'] == true)
        {
            // Get the file type and contents
            $type = $file['type']->scalarval();
            $bits = $file['bits']->scalarval();
            $name = $file['name']->scalarval();
            
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
                $uri['url'] = new XML_RPC_Value($file->getUrl());
                
                $value = new XML_RPC_Value($uri, 'struct');
                
                $response = new XML_RPC_Response($value);
                
                return $response;
            }
            else
            {
                // No url, raise an XML-RPC error
                return new XML_RPC_Response(0, -2, $stored['message']);
            }
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            $response = new XML_RPC_Response (0, $auth['code'], $auth['message']);
            
            return $response;
        }
    }
?>
