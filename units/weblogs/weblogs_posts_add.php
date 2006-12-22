<?php

    global $page_owner;

    if (logged_on) {
    
    
    if (!run("permissions:check", "weblog")) {
        if (logged_on) {
            $page_owner = $_SESSION['userid'];
        } else {
            $page_owner = -1;
        }
    }
    
    $contentTitle = trim(optional_param('title'));
    $contentBody = trim(optional_param('body'));
    
    $redirect = url . user_info('username', $page_owner) . "/weblog/";
    
    $username = $_SESSION['username'];
    $addPost = __gettext("Add a new post"); // gettext variable
    $postTitle = __gettext("Post title:"); // gettext variable
    $postBody = __gettext("Post body:"); // gettext variable
    $Keywords = __gettext("Keywords (Separated by commas):"); // gettext variable
    $keywordDesc = __gettext("Keywords commonly referred to as 'Tags' are words that represent the weblog post you have just made. This will make it easier for others to search and find your posting."); // gettext variable
    $accessRes = __gettext("Access restrictions:"); // gettext variable
    $postButton = __gettext("Post"); // gettext variable
    
    $body = <<< END

<form method="post" name="elggform" action="$redirect" onsubmit="return submitForm();">

    <h2>$addPost</h2>
    
END;

    $body .= templates_draw(array(
                                'context' => 'databoxvertical',
                                'name' => $postTitle,
                                'contents' => display_input_field(array("new_weblog_title",$contentTitle,"text"))
                            )
                            );
                            
    $body .= templates_draw(array(
                                'context' => 'databoxvertical',
                                'name' => $postBody,
                                'contents' => display_input_field(array("new_weblog_post",$contentBody,"weblogtext"))
                            )
                            );

    // Allow plugins to add an icon selection box
    $icon_selection = run("display:icon:select", array('new_weblog_icon', null));
    if (!empty($icon_selection)) {
        $body .= $icon_selection;
    }

    $body .= templates_draw(array(
                                'context' => 'databoxvertical',
                                'name' => $Keywords . "<br />" . $keywordDesc,
                                'contents' => display_input_field(array("new_weblog_keywords","","keywords","weblog"))
                            )
                            );

    $body .= templates_draw(array(
                                'context' => 'databoxvertical',
                                'name' => $accessRes,
                                'contents' => run("display:access_level_select",array("new_weblog_access",default_access))
                            )
                            );

    $body .= run("weblogs:posts:add:fields",$_SESSION['userid']);
    $body .= <<< END
    <p>
        <input type="hidden" name="action" value="weblogs:post:add" />
        <input type="submit" value="$postButton" />
    </p>

</form>
END;

    } else {
        
        $run_result .= "<p>" . __gettext("You must be logged in to post a new weblog entry. You may do so using the login pane to the right of the screen.") . "</p>";
        
    }

    $run_result .= $body;

?>
