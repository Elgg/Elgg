<?php

//    ".$CFG->prefix." browse page

        // Run includes
        require_once("../../includes.php");

        global $search_sql;
        global $filter;
        global $searchtype;
        global $db;
        global $CFG;
        
        define("context", "network");
        
        // Quick config: this changes the number of search results on the page
        $CFG->browser_search_results = 20;
        
        $search_sql = "";
        
        $title = __gettext("Browser");
        $display = optional_param('display', '', PARAM_ALPHANUM);
        $filter = trim(optional_param('filter', ''));
        $searchtype = optional_param('searchtype', '');
        $offset = optional_param('offset', 0, PARAM_INT);
        $displayicons = optional_param('displayicons','');
        $drilldown = optional_param('drilldown',-1,PARAM_INT);
        $offset = optional_param('offset',0,PARAM_INT);

        $access_string = run("users:access_level_sql_where",$_SESSION['userid']);
        $access_string = str_replace("access","t.access",str_replace("owner","t.owner",$access_string));
                        
        if (!empty($searchtype)) {
            $filter = $searchtype . "::" . $filter;
        }
        
        if ($displayicons == '') {
            if (empty($_COOKIE['displayicons'])) {
                $displayicons = 0;
            } else {
                $displayicons = $_COOKIE['displayicons'];
            }
        } else {
            if ($displayicons != 1) {
                $displayicons = 0;
            }
            setcookie("displayicons",$displayicons,time() + (86400 * 365));
        }
        
        $userunderline = "";
        $communitiesunderline = "";
        $allunderline = "";
        
        switch($display) {
            case "communities":
                $usertypefilter = ' u.user_type = "community" ';
                $communitiesunderline = ' style="text-decoration: underline;" ';
                break;
            case "users":
                $usertypefilter = ' u.user_type = "person" ';
                $userunderline = ' style="text-decoration: underline;" ';
                break;
            default:
                $display = '';
                $usertypefilter = ' 1 ';
                $allunderline = ' style="text-decoration: underline;" ';
                break;
        }
            
        $functionbody = "
        
            global \$search_sql;
            global \$filter;
            global \$searchtype;
        
            \$passed = \$matches[1];
            \$searchtype = \$passed;
            \$search_sql = browser_advanced_search(\$passed,'$display',\$filter);
            return \"\";
            
        ";
        
        $filter = preg_replace_callback("/([A-Za-z_0-9]*)\:\:/i",create_function('$matches',$functionbody),$filter);
        
        $displayfilter = $filter;
        if (!empty($searchtype)) {
            $displayfilter = $searchtype . "::" . $displayfilter;
        }
        $displayfilter = htmlspecialchars($displayfilter);
        
        $all = __gettext("All");
        $communities = __gettext("Communities");
        $users = __gettext("Users");
        $browse = __gettext("Browse");
        $filter_text = __gettext("Filter");
        
        $body = <<< END
        
            <p><b>$browse ::</b> 
            
                <a href="index.php?display=all&amp;searchtype=$searchtype&amp;filter=$filter" $allunderline>$all</a> | 
                <a href="index.php?display=users&amp;searchtype=$searchtype&amp;filter=$filter" $userunderline>$users</a> | 
                <a href="index.php?display=communities&amp;searchtype=$searchtype&amp;filter=$filter" $communitiesunderline>$communities</a> | 
            </p>
            
            <form action="index.php" method="get">
                <p>$filter_text : <input type="textbox" size="60" name="filter" value="$displayfilter" />
                <input type="hidden" name="display" value="{$display}" />&nbsp;<input type="submit" value="$filter_text &gt;&gt;" /></p>
            </form>
        
END;
        
        if (empty($search_sql)) {
            $formatted_filter = $db->qstr($filter);
            
            if (empty($filter)) {
                $search_sql = "SELECT u.ident, u.username, u.name, u.icon, u.user_type, COUNT(m.ident) AS members FROM `".$CFG->prefix."users` u JOIN ".$CFG->prefix."friends m ON m.friend = u.ident WHERE " . $usertypefilter . " GROUP BY u.ident ORDER BY members DESC, name DESC";
                $count_sql = "SELECT COUNT(DISTINCT u.ident) AS numberofusers, COUNT(m.ident) AS members FROM `".$CFG->prefix."users` u JOIN ".$CFG->prefix."friends m ON m.friend = u.ident WHERE " . $usertypefilter . "";
            } else {
                if (empty($searchtype)) {
                    $search_sql = "SELECT u.ident, u.username, u.name, u.icon, u.user_type, COUNT(m.ident) AS members FROM ".$CFG->prefix."tags t JOIN ".$CFG->prefix."users u ON u.ident = t.owner JOIN ".$CFG->prefix."friends m ON m.friend = u.ident WHERE ($access_string) AND t.tag = $formatted_filter AND " . $usertypefilter . " GROUP BY u.ident ORDER BY members DESC, name DESC";
                    $count_sql = "SELECT COUNT(DISTINCT u.ident) AS numberofusers, COUNT(m.ident) AS members FROM ".$CFG->prefix."tags t JOIN ".$CFG->prefix."users u ON u.ident = t.owner JOIN ".$CFG->prefix."friends m ON m.friend = u.ident WHERE ($access_string) AND t.tag = $formatted_filter AND " . $usertypefilter . "";
                }
            }
            
        }
        
        $search_sql .= sql_paging_limit($offset, $CFG->browser_search_results);
        
        if ($results = get_records_sql($search_sql)) {
            
            if ($displayicons) {
                $icontoggle = "<a href=\"index.php?display=$display&amp;searchtype=$searchtype&amp;filter=$filter&amp;displayicons=0\">Hide icons</a>";
            } else {
                $icontoggle = "<a href=\"index.php?display=$display&amp;searchtype=$searchtype&amp;filter=$filter&amp;displayicons=1\">Show icons</a>";
            }
            
            $name = __gettext("Name");
            $description = __gettext("Description");
            $connections = __gettext("Connections");
            $posts = __gettext("Posts");
            $type = __gettext("Type");
            
            $body .= <<< END
            
            <table id="search_table" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="10%" colspan="2">$icontoggle</td>
                    <td width="25%" valign="top"><b>$name</b></td>
                    <td width="25%" valign="top"><b>$description</b></td>
                    <td width="12%" valign="top"><b>$connections</b></td>
                    <td width="12%" valign="top"><b>$posts</b></td>
                    <td width="16%" valign="top"><b>$type</b></td>
                </tr>
            
END;
            
            foreach($results as $result) {
                
                $blogposts = count_records("weblog_posts", "weblog", $result->ident);
                $description = get_field("profile_data", "value", "owner", $result->ident, "name", 'minibio');
                
                $icon_html = user_icon_html($result->ident);
                
                $name = htmlspecialchars($result->name);
                
                $iconcode = "<a href=\"{$CFG->wwwroot}{$result->username}\">{$icon_html}</a>";
                if (!$displayicons) {
                    // Uncomment this if we move to Javascript unhide
                    // $iconcode = "<span style=\"display:none\" class=\"iconhide\">".$iconcode."</span>";
                    $iconcode = "&nbsp;";
                }
                
                $rowspan = "";
                $plus = "";
                
                if (!empty($filter)) {
                    if ($drilldown != $result->ident) {
                        $plus = "<a href=\"index.php?display=$display&amp;searchtype=$searchtype&amp;filter=$filter&amp;drilldown=".$result->ident."#drilldown".$result->ident."\">+</a>";
                    } else {
                        $plus = "<a href=\"index.php?display=$display&amp;searchtype=$searchtype&amp;filter=$filter\">-</a>";
                        $rowspan = "rowspan=\"2\"";
                    }
                }
                
                $body .= <<< END
                
                <tr>
                    <td style="border-right: 0" $rowspan>&nbsp;</td>
                    <td $rowspan>$iconcode</td>
                    <td><a href="{$CFG->wwwroot}{$result->username}">{$name}</a></td>
                    <td>{$description}</td>
                    <td>{$result->members}</td>
                    <td>{$blogposts}</td>
                    <td>{$result->user_type}</td>
                </tr>
END;
                
            }
            
            $body .= <<< END
            
            </table>
            
END;

            if (!empty($count_sql) && $search_total = get_record_sql($count_sql)) {
                $search_total = $search_total->numberofusers;
            } else {
                $search_total = 0;
            }
            
            if ($search_total > $CFG->browser_search_results) {
                
                $i = 1;
                while (($i * $CFG->browser_search_results) - $CFG->browser_search_results < $search_total) {
                    
                    $body .= "<a href=\"index.php?display=$display&amp;searchtype=$searchtype&amp;filter=$filter&amp;offset=".(($i * $CFG->browser_search_results) - ($CFG->browser_search_results))."\">";
                    $body .= $i;
                    $body .= "</a> ";
                    $i++;
                    
                }
                
            }


        } else {
        }
        
        templates_page_setup();
        
        $body = templates_draw(array(
                        'context' => 'contentholder',
                        'title' => $title,
                        'body' => $body
                    )
                    );
        
        
        echo templates_page_draw(array($title, $body));

?>