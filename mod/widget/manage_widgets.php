<?php

/*

Elgg Widgets
http://elgg.org/

*/

// Load Elgg framework
@require_once("../../includes.php");

// We need to be logged on for this!

if (isloggedin()) {
    
    global $profile_id, $page_owner;
    
    // Define context
    define("context","widget");
    $user_id = optional_param('owner',$page_owner,PARAM_INT); 
    $page_owner = $user_id;
    
    $top_bit_template = <<<END
	
	<script type="text/javascript" src="{$CFG->wwwroot}mod/yui/build/yahoo/yahoo.js" ></script>
	<script type="text/javascript" src="{$CFG->wwwroot}mod/yui/build/event/event.js" ></script>
	<script type="text/javascript" src="{$CFG->wwwroot}mod/yui/build/dom/dom.js"></script>
	
	<script type="text/javascript" src="{$CFG->wwwroot}mod/yui/build/dragdrop/dragdrop-min.js" ></script>
	<script type="text/javascript" src="{$CFG->wwwroot}mod/yui/build/connection/connection.js" ></script>
	
	<script type="text/javascript" src="js/DDList_kj.js" ></script>
	
	<script type="text/javascript">
		var user_id = {$page_owner};
		var wwwroot = '$CFG->wwwroot';
		
		YAHOO.example.DDApp = function() {
			return {
				init: function() {
					
					%s
					
					new YAHOO.example.DDListBoundary("hidden1");
					new YAHOO.example.DDListBoundary("hidden2");
					new YAHOO.example.DDListBoundary("hidden3");
					new YAHOO.example.DDListBoundary("garbage");
					
					YAHOO.util.DDM.getElement('hidden2').column = 0;
					//YAHOO.util.DDM.getElement('hidden2').did = 100000;
					YAHOO.util.DDM.getElement('hidden3').column = 1;
					// YAHOO.util.DDM.getElement('hidden3').did = 100000;
				}
			};
		} ();
		
		YAHOO.util.Event.addListener(window, "load", YAHOO.example.DDApp.init);
		// YAHOO.util.DDM.useCache = false;
		
		function handle_widget_start_edit(el) {
			id = el.id.substring(4);
			if (current_el) {
				current_el.className = 'sortList';
			}
			el.className = 'sortListCurrent';
			current_el = el;
			YAHOO.util.Connect.asyncRequest('GET', wwwroot+'mod/widget/ajax_start_edit_widget.php?id='+id, { success:start_edit_successHandler, failure:start_edit_failureHandler });
			return false;
		}
		
		function start_edit_successHandler(o) {
			var root = o.responseXML.documentElement; 
			var oForm = root.getElementsByTagName('edit_form')[0].firstChild.nodeValue;
			
			YAHOO.util.DDM.getElement('formarea').innerHTML = '<p>'+ oForm + "</p>"; 
		}
		
		function start_edit_failureHandler(o){ 
			YAHOO.util.DDM.getElement('formarea').innerHTML = "Cannot get edit form: "+o.status + " " + o.statusText;  
		}
		
		function handle_widget_edit() {
			YAHOO.util.Connect.setForm('widget_edit_form');
			YAHOO.util.Connect.asyncRequest('POST', wwwroot+'mod/widget/edit.php', { success:edit_successHandler, failure:edit_failureHandler });
			return false;
		}
		
		function edit_successHandler(o) {
			var root = o.responseXML.documentElement; 
			//var oResult = root.getElementsByTagName('result')[0].firstChild.nodeValue;
			YAHOO.util.DDM.getElement('formarea').innerHTML = '<p>Saved widget.</p>'; 
		}
		
		function edit_failureHandler(o){ 
			YAHOO.util.DDM.getElement('formarea').innerHTML = o.status + " " + o.statusText; 
		}
		
		function handle_widget_move(id,id_before,new_column) {
			if (id_before[0] == 'h') {
				extra = '&where=end';
			} else {
				extra = '&where=before&id2='+id_before.substring(4);
			}
				
			YAHOO.util.Connect.asyncRequest('GET', wwwroot+'mod/widget/ajax_move_widget.php?id='+id.substring(4)+'&column='+new_column+extra, { success:move_successHandler, failure:move_failureHandler });
			return false;
		}
		
		function move_successHandler(o) {
			// no need to do anything
		}
		
		function move_failureHandler(o){ 
			YAHOO.util.DDM.getElement('formarea').innerHTML = "Move failed: "+o.status + " " + o.statusText; 
		}
		
		function handle_widget_delete(el) {
			if (current_el == el) {
				current_el = '';
			}
			
			id = el.id;
			p = el.parentNode;
			p.removeChild(el);  
				
			YAHOO.util.Connect.asyncRequest('GET', wwwroot+'mod/widget/ajax_delete_widget.php?id='+id.substring(4), { success:delete_successHandler, failure:delete_failureHandler });
			return false;
		}
		
		function delete_successHandler(o) {
			YAHOO.util.DDM.getElement('formarea').innerHTML = '<p>Deleted widget.</p>';
		}
		
		function delete_failureHandler(o){ 
			YAHOO.util.DDM.getElement('formarea').innerHTML = "Delete failed: "+o.status + " " + o.statusText; 
		}
		
	</script>
	
	<style type="text/css">
		
		.sortList {
			cursor:move;
			margin:0 0 6px 0;
			padding: 2px 2px;
			border: 1px solid #ccc;
			background-color: #eee;
		}
		
		.sortListHidden { height:10px; font-size:14px; visibility:hidden; }
		
		.sortListWorking { cursor:move; font-size:14px; background-color: #FFFF00;}
		
		.sortListCurrent {
			cursor:move;
			margin-bottom: 4px;
			padding: 2px 2px;
			border: 1px solid #ccc;
			background-color: #ccc;
			font-weight:bold;
		}
		
		.listGroup1 li {
			list-style-type: none;
		}
		
		.listGroup1 {
			list-style-type: none;
			padding: 4px 4px 10px 4px;
			margin: 0px;
			width: 10em;
			font-size: 13px;
			font-family: Arial, sans-serif;
			border: 1px solid #ccc;
			width:200px;
		}
		
		#manage-widgets h2 {
			background:#fff;
			display:block;
			border:none;
			font-size:1em;
			padding:0;
			margin:2px;
		}
		
		#formarea {
			margin:10px 40px 10px 40px;
			background:#efefef;
			padding:5px;
			border:1px solid #ccc;
		}
	
	</style>
	
END;
    $explanation = "<p style=\"height:156px;\"><img src=\"{$CFG->wwwroot}mod/template/images/widgets.gif\" alt=\"widgets layout\" align=\"right\">" .__gettext("Hover your mouse over the name of each widget to find out more about it. Drag widgets from the widgets column into either profile column. You can also move widgets around within the profile columns. Edit any widget by clicking on it.")."</p>";
    $widget_template = <<<END
		$explanation
		<form name="dragDropForm" action="javscript:;">
		<div id="manage-widgets">
		<table border="0">
		<tr>
		<td><h2>Available widgets</h2></td>
		<td>&nbsp;</td>
		<td><h2>Your left column</h2></td>
		<td>&nbsp;</td>
		<td><h2>Your right column</h2></td>
		</tr>
		<tr>
		<td valign="top">
		<ul class="listGroup1">
			<li id="hidden6" class="sortListHidden">Hidden</li>
			%s
			<li id="hidden1" class="sortListHidden">Hidden</li>
		
		</ul>
		</td>
		
		<td>&nbsp;</td>
		<td valign="top">
		<ul class="listGroup1">
			<li id="hidden5" class="sortListHidden">Hidden</li>
			%s
			<li id="hidden2" class="sortListHidden">Hidden</li>
		</ul><br />
		<ul class="listGroup1">
		<li id="garbage"><img src="{$CFG->wwwroot}mod/widget/images/delete.gif" width="16" height="16"></li>
		</ul>
		</td>
		
		<td>&nbsp;</td>
		<td valign="top">
		<ul class="listGroup1">
			<li id="hidden4" class="sortListHidden">Hidden</li>
			%s
			<li id="hidden3" class="sortListHidden">Hidden</li>
		</ul>
		</td>
		</tr>
		</table>
		</div>
		</form>
		<div id="dyn"></div>
		<div id="formarea"></div>
END;
    
    $widget_list_element_template = '<li alt="%s" title="%s" id="wli_%s" class="sortList">%s</li>';
    $column_list_element_template = '<li alt="%s" title="%s" id="eli_%s" class="sortList">%s</li>';
    $widget_list = '';
    $do_init = '';
    
    if (is_array($CFG->widgets->list) && !empty($CFG->widgets->list)) {
        foreach($CFG->widgets->list as $widget) {
            if (!$widget['type']) {
                $widget['type'] = $widget['id'];
            }
            $widget_list .= sprintf($widget_list_element_template,$widget['description'],$widget['description'],$widget['type'],$widget['name']);
            $do_init .= 'new YAHOO.example.DDList("wli_'.$widget['type'].'");'."\n";
        }
    }
    
    $first_column_list = '';
    
    if ($widgets = widget_for_user($page_owner,'profile',0,0)) {
        foreach($widgets as $widget) {
            $name = 'Unknown';
            $description = '';
            foreach($CFG->widgets->list as $widget_class) {
                if ($widget_class['type'] == $widget->type) {
                    $name = $widget_class['name'];
                    $description = $widget_class['description'];
                    break;
                }
            }
            $first_column_list .= sprintf($column_list_element_template,$description,$description,$widget->ident,$name);
            $do_init .= 'new YAHOO.example.DDList("eli_'.$widget->ident.'");'."\n";
            //$do_init .= 'YAHOO.util.DDM.getElement("eli_'.$widget->ident.'").did='.$widget->display_order.';'."\n";
            $do_init .= 'YAHOO.util.DDM.getElement("eli_'.$widget->ident.'").column=0;'."\n";
        }
    }
    
    $second_column_list = '';
    
    if ($widgets = widget_for_user($page_owner,'profile',0,1)) {
        foreach($widgets as $widget) {
            $name = 'Unknown';
            $description = '';
            foreach($CFG->widgets->list as $widget_class) {
                if ($widget_class['type'] == $widget->type) {
                    $name = $widget_class['name'];
                    $description = $widget_class['description'];
                    break;
                }
            }
            $second_column_list .= sprintf($column_list_element_template,$description,$description,$widget->ident,$name);
            $do_init .= 'new YAHOO.example.DDList("eli_'.$widget->ident.'");'."\n";
            //$do_init .= 'YAHOO.util.DDM.getElement("eli_'.$widget->ident.'").did='.$widget->display_order.';'."\n";
            $do_init .= 'YAHOO.util.DDM.getElement("eli_'.$widget->ident.'").column=1;'."\n";
        }
    }
    
    $title = __gettext("Manage Widgets");
    $body = sprintf($top_bit_template,$do_init).sprintf($widget_template,$widget_list,$first_column_list,$second_column_list);
    
} else {
    $body = __gettext("You must be logged-in to manage your widgets");
}

// Output to the screen
templates_page_output($title, $body);

?>