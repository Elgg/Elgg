<?php

    global $page_owner;

    if ($page_owner != -1) {
        if (user_type($page_owner) == "community") {
            $userid = user_info('username', $page_owner);
            $run_result .= "<div class=\"box_community_wiki\">";
            $run_result .= templates_draw(                array(
                    'name' => "Wiki",
                    'context' => "Infobox",
                    'contents' =>
                    "<p align=\"center\"><a
href=\"http://careo.elearning.ubc.ca/cgi-bin/wiki.pl?elgg/$userid\"
target=\"_blank\">" . __gettext("Community Wiki") . "</a></p>"
                    )
                    );
            $run_result .= "</div>";
        }
    }

?>