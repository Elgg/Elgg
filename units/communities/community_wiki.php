<?php

       global $page_owner;

       if ($page_owner != -1) {
               if (run("users:type:get", $page_owner) == "community") {
                       $userid = run ("users:id_to_name", $page_owner);
                       $run_result .= "<div class=\"box_community_wiki\">";
                       $run_result .= run("templates:draw",
                                                                               array(
                                                                                               'name' => "Wiki",
                                                                                               'context' => "Infobox",
                                                                                               'contents' =>
                                                                                               "<p align=\"center\"><a
href=\"http://careo.elearning.ubc.ca/cgi-bin/wiki.pl?elgg/$userid\"
target=\"_blank\">gettext("Community Wiki")</a></p>"
                                                                                               )
                                               );
                       $run_result .= "</div>";
               }
       }

?>