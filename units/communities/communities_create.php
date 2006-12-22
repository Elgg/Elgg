<?php

    $comm_name = '';
    if (isset($_SESSION['comm_name'])) {
        $comm_name = $_SESSION['comm_name'];
    }
    $comm_username = '';
    if (isset($_SESSION['comm_username'])) {
        $comm_username = $_SESSION['comm_username'];
    }

    global $page_owner;
    
    if (logged_on && $page_owner == $_SESSION['userid']) {
    
    $header = __gettext("Create a new community"); // gettext variable
    $communityName = __gettext("Community name:"); // gettext variable
    $communityUsername = __gettext("Username for community:"); // gettext variable
    $buttonValue = __gettext("Create"); // gettext variable

    $run_result .= <<< END

<div class="community_create">
    <p>
        &nbsp;
    </p>
    <h3>
        $header
    </h3>
    <form action="" method="post">
END;

        $run_result .= templates_draw(array(
                                                        'context' => 'databox1',
                                                        'name' => $communityName,
                                                        'column1' => "<input type=\"text\" name=\"comm_name\" value=\"$comm_name\" />"
                                                    )
                                                    );
        $run_result .= templates_draw(array(
                                                        'context' => 'databox1',
                                                        'name' => $communityUsername,
                                                        'column1' => "<input type=\"text\" name=\"comm_username\" value=\"$comm_username\" />"
                                                    )
                                                    );
            
        $run_result .= <<< END
        <p>
            <input type="submit" value="$buttonValue" />
            <input type="hidden" name="action" value="community:create" />
        </p>
        
    </form>
</div>

END;

    }

?>