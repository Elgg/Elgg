<?php

    global $page_owner;
    global $CFG;
    $profile_id = $page_owner;

    $title = __gettext("Search");

    $randomTags = __gettext("Random tags"); // gettext variable
    
    $body = <<< END
        <form id="searchform" name="searchform" action="{$CFG->wwwroot}search/all.php">
            <script language="JavaScript" type="text/javascript">
                <!--
                function submitthis()
                {
                  document.searchform.submit() ;
                }
                -->
            </script>
            <ul>
                <li><input name="tag" type="text" value="" style="width: 110px" />&nbsp;<a href="javascript:submitthis()" style="text-decoration: none">&gt;&gt;</a></li>
                <li><a href="{$CFG->wwwroot}search/tags.php">$randomTags</a></li>
            </ul>
        </form>

END;

    $run_result .= "<li id=\"search\">";
    $run_result .= templates_draw(array(
                                        'context' => 'sidebarholder',
                                        'title' => $title,
                                        'body' => $body
                                        )
                                        );
    $run_result .= "</li>";

?>