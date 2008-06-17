<?php

    /* MoveableType API implementation
     *
     * Full implementation except for the mt.getTrackbackPings call
     */

    function mt_getRecenPostTitles($params, $method)
    {
        // Number of parameters
        $nr_params = (int) 4;
        
        // Do we have the required number of parameters?
        if (count($params) != $nr_params)
        {
            // Raise an XML-RPC error
            return new IXR_Error(-32602, "Invalid method parameters");
        }

        // Parse parameters
        
        $blogid        = $params[0];
        $username      = $params[1];
        $password      = $params[2];
        $numberOfPosts = $params[3];

        // Check credentials
        $auth = run('rpc:auth', array("username" => $username,
                                      "password" => $password));
        
        if ($auth['status'] == true)
        {
            // Get a weblog instance
            $weblog = run('weblogs:instance', array('user_id' => $username,
                                                    'blog_id' => $blogid));

            // Minimum of one, no maximum (for now)
            if ($numberOfPosts >= 1)
            {
                // Get the posts
                $posts  = array_slice($weblog->getPosts(), 0, $numberOfPosts);

                // Global results array
                $result = array();
                
                if (sizeof($posts) > 0)
                {
                    foreach($posts as $post_id)
                    {
                        // Get the post object
                        $post = $weblog->getPost($post_id);

                        // Local array to hold a single post
                        $entry = array();
                        
                        // Fill the post array, same for blogger and metaWeblog
                        $entry['dateCreated'] = new IXR_Date($post->getPosted());
                        $entry['userid']      = (int) $weblog->getIdent(); // is username for now
                        $entry['postid']      = (int) $post->getIdent();
                        $entry['title']       = $post->getTitle();
                    }
                    return $entry;
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

    // returns an array of structs containing String categoryId and String categoryName; on failure, fault.
    function mt_getCategoryList($params, $method)
    {
        // Number of parameters
        $nr_params = 3;
        
        // Do we have the required number of parameters?
        if (count($params) != $nr_params)
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

        $cats = array();
                
        if ($auth['status'] == true)
        {
            // Global results array
            $results = array();

            /* TODO think of a way to possibly use categories (or not) because it doesn't work.
             * Some clients _need_ the categories so just provide them with a default one.
             */

            $results['categoryId'] = '999';
            $results['categoryName'] = 'Default category';

            // Append
            $cats[] = $results;

            return $cats;
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            return new IXR_Error($auth['code'], $auth['message']);
        }
    }

    /* TODO Right now this function only returns a list of tags, which is not quite what the spec demands. 
     * Unfortunately elgg doesn't do categories, so think of something else    
     */
    function mt_getPostCategories($params, $method)
    {
        // Number of parameters
        $nr_params = 3;
        
        // Do we have the required number of parameters?
        if (count($params) != $nr_params)
        {
            // Raise an XML-RPC error
            return new IXR_Error(-32602, "Invalid method parameters");            
        }

        // Parse parameters
        $postId   = (int) $params[0];
        $username = $params[1];
        $password = $params[2];


        // Check credentials
        $auth = run('rpc:auth', array("username" => $username,
                                      "password" => $password));
        
        if ($auth['status'] == true)
        {
            // Global results array
            $results = array();

            /* TODO think of a way to possibly use categories (or not) because it doesn't work.
             * Som clients _need_ the categories so just provide them with a default one.
             */

            $results['categoryId'] = '999';
            $results['categoryName'] = 'Default category';

            return $results;
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            return new IXR_Error($auth['code'], $auth['message']);
        }
    }

    /* TODO This function will always return true. Categories aren't available in elgg so 
     * think of something else
     */
    function mt_setPostCategories($params, $method)
    {
        // Number of parameters
        $nr_params = 4;
        
        // Do we have the required number of parameters?
        if (count($params) != $nr_params)
        {
            // Raise an XML-RPC error
            return new IXR_Error(-32602, "Invalid method parameters");
        }

        // Parse parameters
        $postId     = $params[0];
        $username   = $params[1];
        $password   = $params[2];
        $categories = $params[3];

        // Check credentials
        $auth = run('rpc:auth', array("username" => $username,
                                      "password" => $password));
        
        if ($auth['status'] == true)
        {
            // Array to hold the decoded tag id's
            $tagIds = array();
            
            // Unpack the encoded categories/tags
            foreach($categories as $value)
            {
                // Process the following:
                // (string) $value['categoryId']
                // (boolean)$value['isPrimary']
            }

            /* TODO categories don't map to tags, think of something else */
            return true;
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            return new IXR_Error($auth['code'], $auth['message']);
        }
    }

    function mt_supportedMethods()
    {
        // TODO We will return the handler array. Not very safe since its content depends 
        // on the library loading order. It will do for now.
        // Refactor to use the introsppection API
        
        $result = array();
        
        // $handlers is declared global so we can fetch it via $GLOBALS
        foreach($GLOBALS['handlers'] as $key => $value)
        {
            $result[] = $key;
        }

        return $result;
    }

    function mt_supportedTextFilters($params, $method)
    {
        // Not supported, return an empty array
        
        $result = array();
        
        return $result;
    }

    /* This function just sets the access level to public*/
    function mt_publishPost($params, $method)
    {
        // Number of parameters
        $nr_params = 3;
        
        // Do we have the required number of parameters?
        if ($params->getNumParams() != $nr_params)
        {
            // Raise an XML-RPC error
            return new IXR_Error(-32602, "Invalid method parameters");
        }

        // Parse parameters
        $postId   = $params[0];
        $username = $params[1];
        $password = $params[2];


        // Check credentials
        $auth = run('rpc:auth', array("username" => $username,
                                      "password" => $password));
        
        if ($auth['status'] == true)
        {
            // Publish means public access
            $access = "PUBLISH";

            $post = run('posts:instance', array('id' => $postId));

            $post->setAccess($access);
            
            if ($post->save() == true)
            {
                return true;
            }
            else
            {
                // The status hasn't been saved, raise an XML-RPC error
                return new IXR_Error(-32500, "Unable to publish post");            
            }
            
        }
        else
        {
            // Invalid credentials, raise an XML-RPC error
            return new IXR_Error($auth['code'], $auth['message']);
        }
    }
?>
