<?php

/*

    Elgg Widgets
    http://elgg.org/

*/

// Standard page setup function

    function widget_pagesetup() {
        
    }
    
    
// Initialisation function
    
    function widget_init() {
        
        global $CFG, $function, $db, $METATABLES;
        
        $function['init'][] = $CFG->dirroot . "mod/widget/init.php";
        
        // Initialise the 'allcontent' widget array - i.e., widgets where Javascript is allowed
        
        if (!isset($CFG->widgets->allcontent)) {
            $CFG->widgets->allcontent = array();
        }
        
        // register the widgets that this module provides
        
        $CFG->widgets->list[] = array(
                                                'name' => gettext("Text box"),
                                                'description' => gettext("Displays the text of your choice."),
                                                'type' => "widget::text"
                                        );
        if (!in_array($CFG->prefix . "widget_data",$METATABLES) || !in_array($CFG->prefix . "widgets",$METATABLES)) {
            if (file_exists($CFG->dirroot . "mod/widget/$CFG->dbtype.sql")) {
                modify_database($CFG->dirroot . "mod/widget/$CFG->dbtype.sql");
                //reload system
                header_redirect($CFG->wwwroot);

            } else {
                error("Error: Your database ($CFG->dbtype) is not yet fully supported by the Elgg widgets.  See the mod/widget directory.");
            }
            print_continue("index.php");
            exit;
        }
        
        // Delete users
            listen_for_event("user","delete","widget_user_delete");
    }

// Get widgets for a particular user

    function widget_for_user_sql($user_id,$location=NULL,$location_id=NULL,$column) {
        global $CFG;
        // Get access list for this user
            $where = run("users:access_level_sql_where",$user_id);
            
            $where2 = '';
            
        // Get the sql to select a list of widgets
            if (isset($location)) {
                $where2 .= " AND location = '$location'";
            }
            if (isset($location_id)) {
                $where2 .= " AND location_id = $location_id";
            }           
            if (isset($column)) {
                $where2 .= " AND wcolumn = $column";
            }
            
            $sql = "SELECT * FROM ".$CFG->prefix."widgets WHERE ($where) $where2 AND owner = $user_id ORDER BY display_order ASC";
            
            return $sql;
        }

    function widget_for_user($user_id,$location='',$location_id=0,$column=NULL) {
        
        $widgets = get_records_sql(widget_for_user_sql($user_id,$location,$location_id,$column));
            
        // Return them
            return $widgets;
        
    }
    
    function widget_for_user_count($user_id,$location=NULL,$location_id=NULL, $column=NULL) {
        
        $count = get_record_sql('SELECT COUNT(*) as count FROM ('.widget_for_user_sql($user_id,$location,$location_id,$column).') AS q1');
        
        return $count->count;
    }
    
    function widget_for_user_paginated($user_id,$location=NULL,$location_id=NULL,$column=NULL,$offset=0,$count=0) {
        $widgets = get_records_sql(widget_for_user_sql($user_id,$location,$location_id,$column).sql_paging_limit($offset,$count));
            
        // Return them
        return $widgets;
    }
    
// Gets data with a particular name for a particular widget ID

    function widget_get_data($name, $ident) {
        
        global $db, $CFG;
        
        $value = get_record("widget_data", "name", $name, "widget", $ident);
        if (!empty($value) && !empty($value->value)) {
            $value = $value->value;
        } else {
            $value = '';
        }
        
        return $value;
        
    }
    
    // for compatibility - remove ASAP
    
    function adash_get_data($name, $ident) {
        return widget_get_data($name, $ident);
    }
        
    
// Sets data with a particular name for a particular widget ID

    function widget_set_data($name, $ident, $value) {
        $data = new stdClass;
        $data->name = $name;
        $data->widget = $ident;
        $data->value = $value;
        if ($existing = widget_get_data($name,$ident)) {
            $data->ident = $existing->ident;
            return update_record('widget_data',$data);
        } else {
            return insert_record('widget_data',$data);
        }
    }
    
// Removes data for a particular widget ID

    function widget_remove_data($ident) {
        delete_records('widget_data','widget',$ident);
    }
    
// Removes a widget
    
    function widget_destroy($ident) {
        if ($widget = get_record('widgets','ident',$ident)) {
            if ($widget = plugin_hook("widget","delete",$widget)) {
                delete_records('widgets','ident',$ident);
                widget_remove_data($ident);
            }
        }
    }
    
// Removes all widgets for a user

    function widget_user_delete($object_type, $event, $object) {
        global $CFG;
        if (!empty($object->ident) && $object_type == "user" && $event == "delete") {
            if ($widgets = get_records("widgets","owner",$object->ident)) {
                foreach($widgets as $widget) {
                    widget_destroy($widget->ident);
                }
            }
        }
        return $object;
    }
    
// Creates a widget

function widget_create($location,$location_id,$column,$type,$owner=0,$access='PUBLIC',$display_order=0) {
    
    $widget = new stdClass;
    if ($owner) {
        $widget->owner = $owner;
    } else {
        $widget->owner = $_SESSION['userid'];
    }
    $widget->type = $type;
    $widget->location = $location;
    $widget->location_id = $location_id;
    $widget->wcolumn = $column;
    $widget->access = $access;
    $widget->display_order = $display_order;
    $id = insert_record('widgets',$widget);
    widget_reorder($widget->owner,$widget->location,$widget->location_id);
    
    return $id;
}
    
// generates the query information to ensure that a widget function returns to a display
// page with the correct information
    
    function widget_get_display_query() {
        $q = '';
        $params = array_merge($_GET,$_POST);
        if ($params) {
            foreach($params as $k => $v) {
                if ((strpos($k,'_widget') !== 0) && (strpos($k,'widget_data') !== 0) && $k != 'wid') {
                    $q .= '&amp;'.$k .'='.$v;
                }
            }
        }
        if ($q) {
            $q = substr($q,1);
        }
        return $q;
    }
    
    function widget_get_non_display_query() {
        $q = '';
        if ($_GET) {
            foreach($_GET as $k => $v) {
                if (strpos($k,'_dwidget') !== 0) {
                    $q .= '&amp;'.$k .'='.$v;
                }
            }
        }
        if ($q) {
            $q = substr($q,1);
        }
        return $q;
    }

// asks the appropriate module what URL is used to display this widget
    
    function widget_get_display_url($module) {
        $redirect_url = '';
        $module_widget_display_url = $module . '_widget_display_url';
        if ($module && function_exists($module_widget_display_url)) {
            $redirect_url = $module_widget_display_url();
        }
        return $redirect_url;
    }
    
    
// Returns HTML for a particular widget
// (Assumes a widget object taken from the database)

    function widget_display($widget,$collapsed=0) {
        
        global $CFG;
        global $PAGE;
        
        $widget_menu_template = '<div class="widget_menu">%s</div>';
        
        $widget_template = <<<END
<div class="widget_title">%s</div>
%s
<div class="widget_content">
%s
</div>
<div class="widget_bottom">
</div>
END;
        
        $body = '';
        $title = '';
        
        // get module from the widget type
        $module = '';
        $mod_pos = strpos($widget->type,"::");
        if ($mod_pos) {
            $module = substr($widget->type,0,$mod_pos);
        }
        
        // If the handler for displaying this particular widget type exists,
        // run it - otherwise display nothing

        if ($collapsed) {
            $mod_widget_display = $module . '_widget_display_collapsed';
        } else {
            $mod_widget_display = $module . '_widget_display';
        }
        if ($module && function_exists($mod_widget_display)) {
            $widget_array = $mod_widget_display($widget);
            if (isset($widget_array['content'])) {
                $body = $widget_array['content'];
            } else {
                $body = '';
            }
            if (isset($widget_array['title'])) {
                $title = $widget_array['title'];
            } else {
                $title = '';
            }
            if (isset($widget_array['menu'])) {
                $menu = $widget_array['menu'];
            } else {
                $menu = array();
            }
        } else {
            $result = '';
            $title = '';
            $menu = array();
             if (!empty($CFG->widgets->display[$widget->type])) {
                $body = $CFG->widgets->display[$widget->type]($widget);
             } else {
                 $body = "";
             }
        }
        if ($menu) {
            // menu generation goes here
            $menu_html = '<ul>'."\n";
            foreach ($menu as $menu_item) {
                $menu_html .= '<li><a alt="'.$menu_item['title'].'" title="'.$menu_item['title'].'" href="'.$menu_item['link'].'">'.$menu_item['text'].'</a></li>'."\n";
            }
            $menu_html .= '</ul>'."\n";
        }
        
        if (!empty($menu_html)) {
            $menu_bit = sprintf($widget_menu_template,$menu_html);
        } else {
            $menu_bit = '';
        }
       
        $body = sprintf($widget_template,$title,$menu_bit,$body);
        
        // If we have permission, display edit buttons
        
        if (isloggedin() && run("permissions:check","profile")) {
            
            $q = widget_get_display_query($widget);
            
            // print("widget_get_display_query: $q");
            $edit_msg = gettext("Edit widget");
            $delete_msg = gettext("Delete widget");
            $moveup_msg = gettext("Move up");
            $movedown_msg = gettext("Move down");
            $moveright_msg = gettext("Move right");
            $moveleft_msg = gettext("Move left");
            $img_template = '<img border="0" width="16" height="16" alt="%s" title="%s" src="'.$CFG->wwwroot.'mod/widget/images/%s" />';
            $edit_img = sprintf($img_template,$edit_msg,$edit_msg,"16-em-pencil.png");
            $delete_img = sprintf($img_template,$delete_msg,$delete_msg,"16-em-cross.png");
            $moveup_img = sprintf($img_template,$moveup_msg,$moveup_msg,"16-em-open.png");
            $movedown_img = sprintf($img_template,$movedown_msg,$movedown_msg,"16-em-down.png");
            $moveright_img = sprintf($img_template,$moveright_msg,$moveright_msg,"16-em-right.png");
            $moveleft_img = sprintf($img_template,$moveleft_msg,$moveleft_msg,"16-em-left.png");
            
            $body .= "<div class=\"widget_admin_menu\">";
            $body .= "<a href=\"" . $CFG->wwwroot . "mod/widget/edit.php?wid=" . $widget->ident . "&amp;" . $q . "\">" . $edit_img . "</a>  ";
            $body .= "<a href=\"" . $CFG->wwwroot . "mod/widget/delete.php?wid=" . $widget->ident . "&amp;" . $q . "\">" . $delete_img . "</a>  ";
            
            if (empty($CFG->uses_YUI)) {
                $body .= "<a href=\"" . $CFG->wwwroot . "mod/widget/move.php?_widget_move=up&amp;wid=" . $widget->ident . "&amp;" . $q . "\">" . $moveup_img . "</a>  ";
                $body .= "<a href=\"" . $CFG->wwwroot . "mod/widget/move.php?_widget_move=down&amp;wid=" . $widget->ident . "&amp;" . $q . "\">" . $movedown_img . "</a> ";
                if ($widget->wcolumn == 0) {
                    $body .= "<a href=\"" . $CFG->wwwroot . "mod/widget/move.php?_widget_move=2&amp;wid=" . $widget->ident . "&amp;" . $q . "\">" . $moveright_img . "</a> ";
                } else {
                    $body .= "<a href=\"" . $CFG->wwwroot . "mod/widget/move.php?_widget_move=1&amp;wid=" . $widget->ident . "&amp;" . $q . "\">" . $moveleft_img . "</a> ";
                }
                
            }
            $body .= "</div>";
        }
            
        // Return HTML
        
        return $body;
        
    }
    
// Create a pagination line with links to first, previous, next and last widgets
    
    function widget_get_paginator($url,$owner,$location,$location_id,$offset,$count,$total,$collapsed) {
        // print("In widget_get_paginator: offset: $offset, count: $count, total: $total\n");
        $search_template = gettext("Showing %s to %s of %s results");
        $q = widget_get_non_display_query();
        if ($q) {
            $q .= '&amp;';
        }
        $query_string = "{$q}_dwidget_collapsed=$collapsed&amp;_dwidget_count=$count&amp;_dwidget_total=$total&amp;_dwidget_location=$location&amp;_dwidget_location_id=$location_id&amp;_dwidget_owner=$owner";
        $paging_url ="$url?$query_string&amp;_dwidget_offset=";
        $start_paging_link = '<a href="'.$paging_url.'0#results"><b>&lt;&lt;</b></a>';
        if ($count > $total) {
            $end_paging_link = '<a href="'.$paging_url.'0#results"><b>&gt;&gt;</b></a>';
        } else {
            $end_paging_link = '<b><a href="'.$paging_url.($total-$count).'#results">&gt;&gt;</a></b>';
        }
        
        if ($offset + $count < $total) {
            $new_offset = $offset+$count;
            $report = sprintf($search_template,$offset+1,$new_offset,$total);
            $next_link = '<a href="'."$url?$query_string&amp;_dwidget_offset=".($new_offset).'#results">';
            // $next_link .= gettext('Next');
            $next_link .= '&gt;</a>';
        } else {
            $report = sprintf($search_template,$offset+1,$total,$total);
            $next_link = '&gt;';
        }
        if ($offset > 0) {
            $new_offset = $offset - $count;
            if ($new_offset < 0) {
                $new_offset = 0;
            }           
            $previous_link = '<a href="'."$url?$query_string&amp;_dwidget_offset=".($new_offset).'#results">';
            // $previous_link .= gettext('Previous');
            $previous_link .= '&lt;</a>';
        } else {
            $previous_link = '&lt;';
        }
        
        $paging_style = <<<END
        <style type="text/css">
        .paging_widget {
            width:430px;
            text-align: center;
        }
        .paging_widget a {
            font-weight: bold;
            text-decoration: underline;
        }
        </style>
END;
        
        $pagination = "<div class=\"paging_widget\"><p>$start_paging_link $previous_link $report $next_link $end_paging_link</p></div>";
        return $pagination;
    }
    
// Returns HTML that displays all widgets belonging to a particular user, in the current order
// Optionally these widgets can be restricted to a particular location and location_id, displayed
// in columns, paginated and/or the widgets collapsed (restricted to one line)
    
    function widget_page_display($owner=0,$location='',$location_id=0,$columns=1,$count=0,$collapsed=0) {
        if (!$owner) {
            $owner = $_SESSION['userid'];
        }
        $offset = optional_param('_dwidget_offset',0,PARAM_INT);
        $total = optional_param('_dwidget_total',0,PARAM_INT);
        // print("In widget_page_display: offset: $offset, count: $count, total: $total\n");
        $html = '';
        if ($count) {
            // handle pagination
            if (!$total) {
                $total = widget_for_user_count($owner,$location, $location_id);
            }
            $widgets = widget_for_user_paginated($owner,$location, $location_id,NULL,$offset,$count);
            if ($widgets) {
               $paginator = widget_get_paginator(widget_get_display_url($location),$owner,$location, $location_id,$offset,$count,$total,$collapsed);
               $html .= $paginator;
               $html .= widget_get_html($widgets,$columns,$collapsed);
           }
           $html .= $paginator;
        } else {
            $widgets = widget_for_user($owner,$location, $location_id);
            if ($widgets) {     
                $html .= widget_get_html($widgets,$columns,$collapsed);
            }
        }
        return $html;
    }
   
    function widget_get_html($widgets,$columns,$collapsed) {
       if ($columns) {
           $i = 0;
           $html_odd = '';
           $html_even = '';
           $html = '';
           foreach ($widgets as $widget) {
               if ($widget->wcolumn == 0) {
                   $html_even .= "\n".'<div class="widget">';
                   $html_even .= widget_display($widget,$collapsed);
                   $html_even .= '</div>';
               } elseif ($widget->wcolumn == 1)  {
                   $html_odd .= "\n".'<div class="widget">';
                   $html_odd .= widget_display($widget,$collapsed);
                   $html_odd .= '</div>';
               }
               $i++;
           }
           $html = '<div class="widgets_even">'."\n".$html_even."\n".'</div>'."\n".'<div class="widgets_odd">'."\n".$html_odd."\n".'</div>'."\n";
       } else {
            foreach ($widgets as $widget) {
                $html .= "\n".'<div class="widget">';
                $html .= widget_display($widget,$collapsed);
                $html .= '</div>';
            }
        }
        return $html;
    }
    
    
    
    
// Reorders widgets for a particular user, location and column

    function widget_reorder($owner,$location=NULL,$location_id=NULL,$column=NULL) {
        
        $widgets = widget_for_user($owner,$location,$location_id,$column);
        if (is_array($widgets) && !empty($widgets)) {
            $order = array();
            $i = 1;
            foreach($widgets as $widget) {
                $order[$widget->ident] = $i * 10;
                $i++;
            }
            foreach($order as $ident => $display_order) {
                $widget = new StdClass;
                $widget->display_order = $display_order;
                $widget->ident = $ident;
                update_record('widgets',$widget);
            }
        }
        
    }
    
// Move a widget up
    function widget_moveup($widget) {
        $widget->display_order = $widget->display_order - 11;
        update_record('widgets',$widget);
        widget_reorder($widget->owner,$widget->location,$widget->location_id,$widget->wcolumn);
    }

// Move a widget down
    function widget_movedown($widget) {
        $widget->display_order = $widget->display_order + 11;
        update_record('widgets',$widget);
        widget_reorder($widget->owner,$widget->location,$widget->location_id,$widget->wcolumn);
    }
    
// move a widget to specified column and before the specified position
    
    function widget_move_before($widget,$display_order,$column) {
        $widget->display_order = $display_order - 1;
        $widget->wcolumn = $column;
        update_record('widgets',$widget);
        widget_reorder($widget->owner,$widget->location,$widget->location_id,$widget->wcolumn);
    }
    
    // move a widget to specified column and after the specified position
    
    function widget_move_after($widget,$display_order,$column) {
        $widget->display_order = $display_order + 1;
        $widget->wcolumn = $column;
        update_record('widgets',$widget);
        widget_reorder($widget->owner,$widget->location,$widget->location_id,$widget->wcolumn);
    }
        
    
    function widget_finish_edit_form($widget,$body,$ajax=0) {
        
        global $CFG;
        
        $access_bit = '<br />'.gettext("Access: ") . run("display:access_level_select",array("_widget_access",$widget->access));
        if ($ajax) {
            $form = "<form id=\"widget_edit_form\" method=\"post\">\n" . $body;
            $form .= "<input type=\"hidden\" name=\"_widget_action\" value=\"widget:save:ajax\" />\n";
        } else {
            $form = "<form action=\"" . $CFG->wwwroot . "mod/widget/edit.php\" method=\"post\">\n" . $body;
            $form .= "<input type=\"hidden\" name=\"_widget_action\" value=\"widget:save\" />\n";
        }
        
        $form .= $access_bit;

        $form .= "<input type=\"hidden\" name=\"wid\" value=\"" . $widget->ident . "\" />\n";
        
        // need to pass this information to the edit form so it redirects to the correct display page
        $params = array_merge($_GET,$_POST);
        if ($params) {
            foreach($params as $k => $v) {
                if (strpos($k,'_widget') !== 0 && $k != 'wid') {
                    $form .= "<input type=\"hidden\" name=\"$k\" value=\"" . $v . "\" />\n";
                }
            }
        }
        
        return $form;
    }
    
// Returns HTML for the edit screen on a particular widget
// (Assumes a widget object taken from the database)

    function widget_edit($widget,$ajax=0) {
        
        global $CFG;
        global $PAGE;
        
        // If the handler for displaying this particular widget type exists,
        // run it - otherwise display nothing
        
        $body = '';
        
        // get module from the widget type
        $module = '';
        $mod_pos = strpos($widget->type,"::");
        if ($mod_pos) {
            $module = substr($widget->type,0,$mod_pos);
        }
        
        //print 'module:'.$module;
        
        if (isloggedin() && run("permissions:check","profile")) {
            
            $mod_widget_edit = $module . '_widget_edit';
            if ($module && function_exists($mod_widget_edit)) {
                $body = $mod_widget_edit($widget);
            } else {
                if (!empty($CFG->widgets->edit[$widget->type])) {
                    $body = $CFG->widgets->edit[$widget->type]($widget);
                } else {    
                    $body = "";
                }
            }
        }
        // Stick it in an appropriate form for saving
        $body = widget_finish_edit_form($widget,$body,$ajax);
        if ($ajax) {
            $body .= "<input type=\"button\" onClick=\"javascript:handle_widget_edit();return true;\" value=\"" . gettext("Save widget") . "\" />";
        } else {
            $body .= "<input type=\"submit\" value=\"" . gettext("Save widget") . "\" />";
        }
        $body .= "</p>\n</form>\n";
        // Return HTML
        return $body;
        
    }
    
// Functions to display and edit plain text widgets

    function widget_widget_display($widget) {
         switch($widget->type) {
            case 'widget::text': 
                return widget_text_widget_display($widget);
                break;
        }
    }
    
    function widget_widget_edit($widget) {
         switch($widget->type) {
            case 'widget::text': 
                return widget_text_widget_edit($widget);
                break;
        }
    }
    
    function widget_text_widget_display($widget) {
        
        global $CFG;
        
        $text_title = widget_get_data("widget_text_title",$widget->ident);
        $text_body = widget_get_data("widget_text_body",$widget->ident);
        
        if (empty($text_body)) {
            $text_body = gettext("This text box is undefined. If you are the widget owner, click 'edit widget' to add your own content.");
        }
        
        $body = "<p>" . nl2br($text_body) . "</p>";
        
        return array('title'=>$text_title,'content'=>$body);
        
    }
    
    function widget_text_widget_edit($widget) {
        
        global $CFG, $page_owner;

        $widget_text_title = widget_get_data("widget_text_title",$widget->ident);
        $widget_text_body = widget_get_data("widget_text_body",$widget->ident);


        $body = "<h2>" . gettext("Text box") . "</h2>";
        $body .= "<p>" . gettext("This widget displays the text content of your choice. All you need to do is enter the title and body below:") . "</p>";

        $body .= "<p>" . display_input_field(array("widget_data[widget_text_title]",$widget_text_title,"text")) . "</p>";
        $body .= "<p>" . display_input_field(array("widget_data[widget_text_body]",$widget_text_body,"longtext")) . "</p>";

        return $body;
    }
    
?>