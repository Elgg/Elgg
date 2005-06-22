<?php

    /* MoveableType API implementation
     *
     * Full implementation except for the mt.getTrackbackPings call
     */

    // Prepare a list of handlers to be loaded into the XML-RPC server

    $handlers_mt = array('mt.getRecentPostTitles'  => array('function' => 'mt_getRecenPostTitles'),
                         'mt.getCategoryList'      => array('function' => 'mt_getCategoryList'),
                         'mt.getPostCategories'    => array('function' => 'mt_getPostCategories'),
                         'mt.setPostCategories'    => array('function' => 'mt_setPostCategories'),
                         'mt.supportedMethods'     => array('function' => 'mt_supportedMethods'),
                         'mt.supportedTextFilters' => array('function' => 'mt_supportedTextFilters'),
                         'mt.publishPost'          => array('function' => 'mt_publishPost'));

    // Add the handlers to the global handlers array
    $handlers = $handlers + $handlers_mt;

    function mt_getRecenPostTitles($params)
    {
        // Number of parameters
        $nr_params = 4;
        
        // Do we have the required number of parameters?
        if ($params->getNumParams() != $nr_params)
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

        $param          = $params->getParam(3);
        $numberOfPosts  = (int) $param->scalarval();

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
                        $entry['dateCreated'] = new XML_RPC_Value(date('Ymd\TH:i:s', $post->getPosted()), 'dateTime.iso8601');
                        $entry['userid']      = new XML_RPC_Value($weblog->getId()); // is username for now
                        $entry['postid']      = new XML_RPC_Value($post->getIdent());
                        $entry['title']       = new XML_RPC_Value($post->getTitle());
                        
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

    // returns an array of structs containing String categoryId and String categoryName; on failure, fault.
    function mt_getCategoryList($params)
    {
        // Number of parameters
        $nr_params = 3;
        
        // Do we have the required number of parameters?
        if ($params->getNumParams() != $nr_params)
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
            // Global results array
            $results = array();

            /* TODO think of a way to possibly use categories (or not) because it doesn't work.
             * Some clients _need_ the categories so just provide them with a default one.
             */

            $tag['categoryId'] = new XML_RPC_Value('999');
            $tag['categoryName'] = new XML_RPC_Value('Default category');

            // Construct the encoded post struct
            $value = new XML_RPC_Value($tag, 'struct');

            // Add it to the results array
            $result[] = $value;

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

    /* TODO Right now this function only returns a list of tags, which is not quite what the spec demands. 
     * Unfortunately elgg doesn't do categories, so think of something else    
     */
    function mt_getPostCategories($params)
    {
        // Number of parameters
        $nr_params = 3;
        
        // Do we have the required number of parameters?
        if ($params->getNumParams() != $nr_params)
        {
            // Raise an XML-RPC error
            $response = new XML_RPC_Response (0, -32602, "Invalid method parameters");
            
            return $response;
        }

        // Parse parameters
        $param    = $params->getParam(0);
        $postId   = (int) $param->scalarval();

        $param    = $params->getParam(1);
        $username = $param->scalarval();

        $param    = $params->getParam(2);
        $password = $param->scalarval();


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

            $tag['categoryId'] = new XML_RPC_Value('999');
            $tag['categoryName'] = new XML_RPC_Value('Default category');

            // Construct the encoded post struct
            $value = new XML_RPC_Value($tag, 'struct');

            // Add it to the results array
            $result[] = $value;

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

    /* TODO This function will always return true. Categories aren't available in elgg so 
     * think of something else
     */
    function mt_setPostCategories($params)
    {
        // Number of parameters
        $nr_params = 4;
        
        // Do we have the required number of parameters?
        if ($params->getNumParams() != $nr_params)
        {
            // Raise an XML-RPC error
            $response = new XML_RPC_Response (0, -32602, "Invalid method parameters");
            
            return $response;
        }

        // Parse parameters
        $param      = $params->getParam(0);
        $postId     = $param->scalarval();

        $param      = $params->getParam(1);
        $username   = $param->scalarval();

        $param      = $params->getParam(2);
        $password   = $param->scalarval();

        $param      = $params->getParam(3);
        $categories = $param->scalarval();

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
                $category = $value->scalarval();
                $tagIds[] = $category['categoryId']->scalarval();
            }

            /* TODO categories don't map to tags, think of something else */

            $value = new XML_RPC_Value(true, 'boolean');

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

    function mt_supportedMethods()
    {
        // TODO We will return the handler array. Not very safe since its content depends 
        // on the library loading order. It will do for now.
        
        $result = array();
        
        // $handlers is declared global so we can fetch it via $GLOBALS
        foreach($GLOBALS['handlers'] as $key => $value)
        {
            $result[] = new XML_RPC_Value($key);
        }
        
        $value = new XML_RPC_Value($result, 'array');

        $response = new XML_RPC_Response($value);
        
        return $response;
    }

    function mt_supportedTextFilters($params)
    {
        // Not supported, return an empty array
        
        $result = array();
        
        $value = new XML_RPC_Value($result, 'array');
        
        $response = new XML_RPC_Response($value);

        return $response;
    }

    /* This function just sets the access level to public*/
    function mt_publishPost($params)
    {
        // Number of parameters
        $nr_params = 3;
        
        // Do we have the required number of parameters?
        if ($params->getNumParams() != $nr_params)
        {
            // Raise an XML-RPC error
            $response = new XML_RPC_Response (0, -32602, "Invalid method parameters");
            
            return $response;
        }

        // Parse parameters
        $param    = $params->getParam(0);
        $postId   = $param->scalarval();

        $param    = $params->getParam(1);
        $username = $param->scalarval();

        $param    = $params->getParam(2);
        $password = $param->scalarval();


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
                $value = new XML_RPC_Value(true, 'boolean');

                $response = new XML_RPC_Response($value);

                return $response;
            }
            else
            {
                // The status hasn't been saved, raise an XML-RPC error
                $response = new XML_RPC_Response (0, -32500, "Unable to publish post");            
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
