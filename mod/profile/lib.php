<?php
function profile_pagesetup() {
	// register links -- 
	global $profile_id;
	global $PAGE;
	global $CFG;

	// don't clobber $page_owner, use a 
	// local $pgowner instead for clarity
	$pgowner = $profile_id;

	if (isloggedin()) {
		if (defined("context") && context == "profile" && $pgowner == $_SESSION['userid']) {
			$PAGE->menu[] = array (
				'name' => 'profile',
				'html' => '<li><a href="' . $CFG->wwwroot . $_SESSION['username'] . '/profile/" class="selected">' . __gettext("Your Profile") . '</a></li>');
		} else {
			$PAGE->menu[] = array (
				'name' => 'profile',
				'html' => '<li><a href="' . $CFG->wwwroot . $_SESSION['username'] . '/profile/">' . __gettext("Your Profile") . '</a></li>');
		}

		if (profile_permissions_check("profile") && defined("context") && context == "profile") {

			if (user_type($pgowner) == "person") {
				$PAGE->menu_sub[] = array (
					'name' => 'profile:edit',
					'html' => '<a href="' . $CFG->wwwroot . 'profile/edit.php?profile_id=' . $pgowner . '">' . __gettext("Edit this profile") . '</a>');

				$PAGE->menu_sub[] = array (
					'name' => 'profile:picedit',
					'html' => '<a href="' . $CFG->wwwroot . '_icons/?context=profile&amp;profile_id=' . $pgowner . '">' . __gettext("Change site picture") . '</a>');
				if (!empty ($CFG->uses_YUI)) {
					$PAGE->menu_sub[] = array (
						'name' => 'profile:widget:manage',
						'html' => '<a href="' . $CFG->wwwroot . 'mod/widget/manage_widgets.php">' . __gettext("Manage widgets") . '</a>');
				} else {
					$PAGE->menu_sub[] = array (
						'name' => 'profile:widget:add',
						'html' => '<a href="' . $CFG->wwwroot . 'mod/profile/add.php?owner=' . $pgowner . '">' . __gettext("Add widget") . '</a>');
				}
			}
		}
	}

	$PAGE->search_menu[] = array (
		'name' => __gettext("People"), 'user_type' => 'person');

}

function profile_init() {

	global $CFG, $messages, $function, $metatags, $data;

	// Check to see if the profile config file doesn't exist
	if (!isset ($CFG->profilelocation)) {
		$CFG->profilelocation = $CFG->dirroot . "mod/profile/";
	} else {

		if (!file_exists($CFG->profilelocation . "profile.config.php")) {
			if (!copy($CFG->dirroot . "mod/profile/profile.config.php", $CFG->profilelocation . "profile.config.php")) {
				$CFG->profilelocation = $CFG->dirroot . "mod/profile/";
			}
		}

	}

	$css = file_get_contents($CFG->dirroot . "mod/profile/css");
	$css = str_replace("{{url}}", $CFG->wwwroot, $css);
	$metatags .= $css;

    
    // Profile initialisation
    $function['profile:init'][] = dirname(__FILE__) . "/lib/function_init.php";
    // $function['profile:init'][] = $CFG->dirroot . "units/profile/function_editfield_defaults.php";
    $function['profile:init'][] = dirname(__FILE__) . "/lib/function_upload_foaf.php";
    $function['profile:init'][] = $CFG->profilelocation . "profile.config.php";
    
    // Initialisation for the search function
    $function['search:init'][] = dirname(__FILE__) . "/lib/function_init.php";
	$function['search:init'][] = $CFG->profilelocation . "profile.config.php";

    $function['search:all:tagtypes'][] = dirname(__FILE__) . "/lib/function_search_all_tagtypes.php";
    $function['search:all:tagtypes:rss'][] = dirname(__FILE__) . "/lib/function_search_all_tagtypes_rss.php";
        
    // Function to search through profiles
    $function['search:display_results'][] = dirname(__FILE__) . "/lib/function_search.php";
    $function['search:display_results:rss'][] = dirname(__FILE__) . "/lib/function_search_rss.php";
        
    // Functions to view and edit individual profile fields        
    $function['profile:editfield:display'][] = dirname(__FILE__) . "/lib/function_editfield_display.php";
    $function['profile:field:display'][] = dirname(__FILE__) . "/lib/function_field_display.php";
    
    // Function to edit all profile fields
    $function['profile:edit'][] = dirname(__FILE__) . "/lib/function_edit.php";
        
    // Function to view all profile fields
    $function['profile:view'][] = dirname(__FILE__) . "/lib/function_view.php";
        
    // Function to display user's name
    $function['profile:display:name'][] = dirname(__FILE__) . "/lib/function_display_name.php";
        
    $function['profile:user:info'][] = dirname(__FILE__) . "/lib/profile_user_info.php";
    
    // Descriptive text
    $function['content:profile:edit'][] = dirname(__FILE__) . "/lib/content_edit.php";

    // Establish permissions
    $function['permissions:check'][] = dirname(__FILE__) . "/lib/permissions_check.php";
        
    // FOAF
    $function['foaf:generate:fields'][] = dirname(__FILE__) . "/lib/generate_foaf_fields.php";
    $function['vcard:generate:fields:adr'][] = dirname(__FILE__) . "/lib/generate_vcard_adr_fields.php";
                
    // Actions to perform when an access group is deleted
    $function['groups:delete'][] = dirname(__FILE__) . "/lib/groups_delete.php";
        
    // Publish static RSS file of posts and files
    $function['profile:rss:publish'][] = dirname(__FILE__) . "/lib/function_rss_publish.php";

	// Delete users
	listen_for_event("user", "delete", "profile_user_delete");

	// Add items to the dashboard if it exists
	//$CFG->widgets->display['profile'] = "profile_widget_display";
	//$CFG->widgets->edit['profile'] = "profile_widget_edit";
	$CFG->widgets->list[] = array (
		'name' => __gettext("Profile widget"), 
		'description' => __gettext("Displays the contents of a profile field."), 
		'type' => "profile::profile");
		
	$CFG->widgets->list[] = array (
		'name' => __gettext("Friends widget"), 
		'description' => __gettext("Displays the icons of your most recently logged-in friends."), 
		'type' => "profile::friends");
	/*
	$CFG->widgets->list[] = array(
	                                    'name' => __gettext("Files widget"),
	                                    'description' => __gettext("Displays images of some of your files."),
	                                    'type' => "profile::files"
	                            );
	*/
}

function profile_permissions_check($object) {
	global $page_owner;

	if ($object === "profile" && ($page_owner == $_SESSION['userid'] || user_flag_get("admin", $_SESSION['userid']))) {
		return true;
	}
	return false;
}

function profile_widget_display($widget) {

	global $CFG, $profile_id, $data, $page_owner, $db;
	static $profile;

	if ($widget->type == 'profile::profile') {

		$profile_id = $page_owner;

		require_once ($CFG->dirroot . 'profile/profile.class.php');

		$profile_field = widget_get_data("profile_widget_field", $widget->ident);
		$profile_id = $widget->owner;

		$title = __gettext("Profile widget");
		$body = "<p>" . __gettext("This profile box is undefined.") . "</p>";

		if (!isset ($profile)) {
			$profile = new ElggProfile($profile_id);
		}

		$field = null;

		$user_type = user_info("user_type", $widget->owner);

		foreach ($data['profile:details'] as $field_row) {
			if ($field_row->internal_name == $profile_field && (empty ($field_row->user_type) || $field_row->user_type == $user_type)) {
				$field = $field_row;
			}
		}

		$title = $field->name;
		$value = get_record_sql("select * from " . $CFG->prefix . "profile_data where owner = " . $widget->owner . " and name = " . $db->qstr($field->internal_name));
		if (run("users:access_level_check", $value->access)) {
			$body = display_output_field(array (
				$value->value,
				$field->field_type,
				$field->internal_name,
				$field->name,
				$value->ident
			));
		} else {
			$body = "";
		}

		return array (
			'title' => $title,
			'content' => $body
		);
	}
	elseif ($widget->type == 'profile::friends') {
		return profile_friends_widget_display($widget->owner);
	}
	elseif ($widget->type == 'profile::files') {
		return profile_files_widget_display($widget->owner);
	}

}

function profile_show_thumbs($id_list, $list_type) {
	global $profile_id, $page_owner;
	global $CFG, $USER;

	// Given a series of IDs as a parameter, will display a box containing the icons and names of each specified user, community or file
	// $parameter[0] is the title of the box; $parameter[1..n] is the user ID

	$body = "";
	$body .= "<table>\n\t<tr>\n";
	$cellnum = -1;
	if ($list_type == 'files') {
		$in_a_row = 3;
	} else {
		$in_a_row = 5;
	}

	foreach ($id_list as $key => $ident) {

		if ($list_type == 'files') {
			if ($info = get_record_sql('SELECT folder, title, originalname FROM ' . $CFG->prefix . 'files ' .
				'WHERE ident = ?', array ($page_owner,$ident))) {
				$displayname = $info->title;
				$icon_url = $CFG->wwwroot . '_icon/file/' . $ident;
				$username = user_info('username', $page_owner);
				$object_url = $CFG->wwwroot . $username . '/files/' . $info->folder . '/' . $ident . '/' . $info->originalname;
			}
		} else {

			$ident = (int) $ident;
			$info = get_record('users', 'ident', $ident);
			$_SESSION['user_info_cache'][$ident] = $info;

			$icon = user_info('icon', $ident);
			$icon_url = $CFG->wwwroot . '_icon/user/' . $icon . '/w/50';

			$info = $_SESSION['user_info_cache'][$ident];
			$displayname = run("profile:display:name", $info->ident);
			$usermenu = '';
			$object_url = $CFG->wwwroot . $info->username . '/';
		}

		if ($info) {
			$cellnum++;
			if ($cellnum % $in_a_row == 0 && $cellnum > 0) {
				$body .= "</tr><tr>";
			}
			if ($list_type == 'files') {
				$body .=<<< END
                <td>
                <a href="$object_url">
                    <img border="0" src="$icon_url" alt="{$displayname}" title="{$displayname}" />
                </a>
                </td>
END;
			} else {
				$body .=<<< END
                <td>
                <div style="clear:right;">
                <a href="$object_url">
                    <img border="0" src="$icon_url" alt="{$displayname}" title="{$displayname}" />
                </a>
                </div>
                <div>
                <a href="{$CFG->wwwroot}{$info->username}/">{$info->username}</a>
                </div>
                </td>
END;
			}
		}
	}

	$body .= "\t</tr>\n</table>\n";

	return $body;
}

function profile_files_widget_display($userid) {
	global $CFG;
	$file_list = array ();
	$where1 = run("users:access_level_sql_where", $_SESSION['userid']);
	if ($files = get_records_sql('SELECT * FROM ' . $CFG->prefix . 'files WHERE files_owner = ' . $userid . ' AND ' . $where1 . ' LIMIT 9')) {
		$file_count = count_records('files', 'files_owner', $userid);
		foreach ($files as $file) {
			//if (run("users:access_level_check",$file->access) == true || $file->owner == $_SESSION['userid']) {
			$file_list[] = (int) $file->ident;
			//}
		}
	}
	if ($file_list) {
		$username = user_info('username', $userid);
		$title = __gettext('Files');
		$menu = array (
			array (
				'text' => __gettext('View all'
			),
			'link' => $CFG->wwwroot . $username . '/files',
			'title' => $file_count . ' ' . ($file_count == 1 ? __gettext("file"
		) : __gettext("files"))));
		$content = profile_show_thumbs($file_list, 'files');
		$widget_array = array (
			'title' => $title,
			'menu' => $menu,
			'content' => $content
		);
	} else {
		$widget_array = array ();
	}
	return $widget_array;
}

function profile_friends_widget_display($userid) {
	global $CFG;

	$html = '';
	$friends = array ();
	if ($result = get_records_sql('SELECT DISTINCT u.ident,1 FROM ' . $CFG->prefix . 'friends f
	                               JOIN ' . $CFG->prefix . 'users u ON u.ident = f.friend
	                               WHERE f.owner = ? AND u.user_type = ? order by u.last_action desc LIMIT 9', array (
			$userid,
			'person'
		))) {
		$friend_count = get_record_sql('SELECT count(*) as count FROM ' . $CFG->prefix . 'friends f
		                               JOIN ' . $CFG->prefix . 'users u ON u.ident = f.friend
		                               WHERE f.owner = ? AND u.user_type = ?', array (
			$userid,
			'person'
		));
		foreach ($result as $row) {
			$friends[] = (int) $row->ident;
		}
		if ($userid != $_SESSION['userid']) {
			$link = url . "_friends/?owner=$userid";
		} else {
			$link = url . $_SESSION['username'] . "/friends/";
		}
		$title = __gettext('Friends');
		$menu_array = array (
			array (
				'text' => __gettext('View all Friends'),
				'link' => $link,
				'title' => $friend_count->count . ' ' . ($friend_count->count == 1 ? __gettext("friend") : __gettext("friends"))));
		$content = profile_show_thumbs($friends, 'users');
		$widget_array = array (
			'title' => $title,
			'menu' => $menu_array,
			'content' => $content
		);
	} else {
		$widget_array = array ();
	}
	return $widget_array;
}

function profile_widget_edit($widget) {

	global $CFG, $profile_id, $data, $page_owner;
	static $profile;

	if ($widget->type == 'profile::profile') {

		$profile_id = $page_owner;

		require_once ($CFG->dirroot . 'profile/profile.class.php');

		$profile_field = widget_get_data("profile_widget_field", $widget->ident);

		if (!isset ($profile)) {
			$profile = new ElggProfile($profile_id);
		}

		$body = "<h2>" . __gettext("Profile widget") . "</h2>";
		$body .= "<p>" . __gettext("Select a profile field below; the widget will then display the profile content from this field.") . "</p>";

		$body .= "<select name=\"widget_data[profile_widget_field]\">";

		$user_type = user_info("user_type", $widget->owner);

		foreach ($data['profile:details'] as $field_row) {

			if (empty ($field_row->user_type) || $field_row->user_type == $user_type) {
				if ($field_row->internal_name == $profile_field) {
					$selected = "selected=\"selected\"";
				} else {
					$selected = "";
				}

				$body .= "<option value=\"" . $field_row->internal_name . $selected . "\">" . $field_row->name . "</option>\n";
			}
		}

		$body .= "</select>";

	}
	elseif ($widget->type == 'profile::friends') {
		// can't edit this widget for now
		$body = '';
	}
	elseif ($widget->type == 'profile::files') {
		// can't edit this widget for now
		$body = '';
	}

	return $body;

}
function profile_page_owner() {
	if ($profile_name = optional_param('profile_name')) {
		if ($profile_id = user_info_username('ident', $profile_name)) {
			return $profile_id;
		}
	}
	if ($profile_id = optional_param("profile_id", 0, PARAM_INT)) {
		return $profile_id;
	}

}

function profile_user_delete($object_type, $event, $object) {

	global $CFG, $data;
	if (!empty ($object->ident) && $object_type == "user" && $event == "delete") {
		if (is_array($data['profile:details']) && !empty ($data['profile:details'])) {
			foreach ($data['profile:details'] as $profiletype) {
				if ($profiletype->field_type == "keywords") {
					delete_records('tags', 'owner', $object->ident, 'tagtype', $profiletype->internal_name);
				}
			}
		}
		delete_records('tags', 'owner', $object->ident);
	}
	return $object;

}

/**
 * Gets the value of a particular profile item.
 *
 * @uses $CFG
 * @param $userid int The ident of the user we're querying.
 * @param $profile_field string The short name of the profile item we're querying.
 * @return string The value of the profile item - or a blank string.
 */

function get_profile_item($userid, $profile_field) {

	global $CFG, $db, $data;
	$body = "";

	foreach ($data['profile:details'] as $field_row) {
		if ($field_row->internal_name == $profile_field) {
			$field = $field_row;
		}
	}
	if (!empty ($field)) {
		$value = get_record_sql("select * from {$CFG->prefix}profile_data where owner = {$userid} and name = " . $db->qstr($profile_field));
		if (run("users:access_level_check", $value->access)) {
			$body = display_output_field(array (
				$value->value,
				$field->field_type,
				$field->internal_name,
				$field->name,
				$value->ident
			));
		}
	}

	return $body;

}

function profile_url($object_id, $object_type) {
    global $CFG;

    $url = '';

     switch ($object_type) {
        case 'profile::':
           $username = user_info('username', $object_id);

            if (empty($username)) {
                trigger_error(__FUNCTION__.': user does not exists.', E_USER_WARNING);
            } else {
                $url = $CFG->wwwroot . $username . '/';
            }
            break;
        case 'profile::profile':
            $username = user_info('username', $object_id);

            if (empty($username)) {
                trigger_error(__FUNCTION__.': user does not exists.', E_USER_WARNING);
            } else {
                $url = $CFG->wwwroot . $username . '/profile';
            }
            break;
        case 'profile::edit':
            $url = $CFG->wwwroot . 'profile/edit.php?profile_id=' . $object_id;
            break;
    }

    return $url;
}
?>