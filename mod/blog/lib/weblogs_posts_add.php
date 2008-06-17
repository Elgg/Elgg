<?php

    global $page_owner;
    global $profile_id;
    global $CFG;
    global $messages;

    if (logged_on) {


   if (!run("permissions:check", "weblog")) {
        if (logged_on) {
            $page_owner = $_SESSION['userid'];
        } else {
            $page_owner = -1;
        }
    }
    //Getting the field from the context extension
    $extensionContext = trim(optional_param('extension','weblog'));

    if (!run("permissions:check", "weblog")) { // just check if the logged on user can add posts
      $messages[] = __gettext("Permission denied");
      $messages[] = __gettext("You can modify only your own content!");
      $redirect_url = url . user_info('username', $_SESSION['userid']) . "/$extensionContext/";
      header_redirect($redirect_url);
    }

    $contentType = blog_get_extension($extensionContext, 'type', __gettext('Post'));
    $extraField = blog_get_extension($extensionContext, 'field');
    $extraValue = blog_get_extension($extensionContext, 'values');

    $contentTitle = trim(optional_param('title'));
    $contentBody = trim(optional_param('body'));

    $redirect = url . user_info('username', $page_owner) . "/$extensionContext/";

    $username = $_SESSION['username'];
    $addPost = sprintf(__gettext("Add a new %s"),$contentType);
    $postTitle = sprintf(__gettext("%s title:"),$contentType);
    $postBody = sprintf(__gettext("%s body:"),$contentType); 
    $Keywords = __gettext("Keywords (Separated by commas):"); 
    $keywordDesc = __gettext("Keywords commonly referred to as 'Tags' are words that represent the weblog post you have just made. This will make it easier for others to search and find your posting."); 
    $accessRes = __gettext("Access restrictions:"); // gettext variable
    $postButton = __gettext("Publish"); // gettext variable



    $body = <<< END

<form method="post" name="elggform" action="$redirect" onsubmit="return submitForm();" enctype="multipart/form-data">

    <h2>$addPost</h2>

END;
    
    if(isset($CFG->assign_field) && $CFG->assign_field){
      $body .= run("weblogs:assign:field");
    }
                           
    $body .= templates_draw(array(
                                'context' => 'databoxvertical',
                                'name' => $postTitle,
                                'contents' => display_input_field(array("new_weblog_title",$contentTitle,"text"))
                            )
                            );

    // Add the extension field if it is avaible
    $body .= run("weblog:posts:extrafield",array($extensionContext,$extraField, $extraValue));

    // Add the fields before the post textarea
    //$body .= run("weblogs:posts:add:fields:before",$_SESSION['userid']);
    $body .= run("weblogs:posts:add:fields:before",$page_owner);

    // Add the weblog toolbar
    $buttons = run("display:content:toolbar");
    if(!empty($buttons)){
      $body.=$buttons;
    }


    $body .= templates_draw(array(
                                'context' => 'databoxvertical',
                                'name' => $postBody,
                                'contents' => display_input_field(array("new_weblog_post",$contentBody,"weblogtext"))
                            )
                            );

    // Add the fields after the post textarea
    //$body .= run("weblogs:posts:add:fields:after",$_SESSION['userid']);
    $body .= run("weblogs:posts:add:fields:after",$page_owner);


    $body .= templates_draw(array(
                                'context' => 'databox',
                                'name' => $Keywords,
                                'column1' => "<i>$keywordDesc</i>",
                                'column2' => display_input_field(array("new_weblog_keywords","","keywords","weblog"))
                            )
                            );

    $body .= templates_draw(array(
                                'context' => 'databoxvertical',
                                'name' => $accessRes,
                                'contents' => run("display:access_level_select",array("new_weblog_access",default_access))
                            )
                            );

    $body .= <<< END
    <p>
        <input type="hidden" name="action" value="weblogs:post:add" />
        <input type="hidden" name="extension" value="{$extensionContext}" />
        <input type="submit" value="$postButton" />
    </p>

</form>
END;

    } else {

        $body = '';
        $run_result .= "<p>" . __gettext("You must be logged in to post a new entry. You may do so using the login pane to the right of the screen.") . "</p>";

    }

    $run_result .= $body;

?>
