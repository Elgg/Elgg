<?php
/*
 * Created on Sep 20, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2007
 */
function admin_pagesetup() {
	global $PAGE,$CFG;

/*
    if (isadmin()) {
        $PAGE->menu_top [] = array( 'name' => 'admin',
                                    //'html' => a_href("{$CFG->wwwroot}_admin/",
                                    //                "Administration"));
                                    'html' => "<li><a href=\"" . $CFG->wwwroot . "mod/admin/\">" . __gettext("Administration") . "</a></li>");
    }
*/	
	if (defined("context") && context == "account") {
		$PAGE->menu_sub[] = array (
			'name' => 'user:edit',
			'html' => a_href("{$CFG->wwwroot}_userdetails/",__gettext("Edit user details")));
		$PAGE->menu_sub[] = array (
			'name' => 'user:icon',
			'html' => a_href("{$CFG->wwwroot}_icons/",__gettext("Your site picture")));
	}

	if (defined("context") && context == "admin" && isloggedin() && user_flag_get("admin", $_SESSION['userid'])) {
		$PAGE->menu_sub[] = array (
			'name' => 'admin',
			'html' => a_href(get_url(-1, 'admin::main'),__gettext("Main")));

		$PAGE->menu_sub[] = array (
			'name' => 'admin:users:add',
			'html' => a_href(get_url(-1, 'admin::users::add'),__gettext("Add users")));

		$PAGE->menu_sub[] = array (
			'name' => 'admin:users',
			'html' => a_href(get_url(-1, 'admin::users'),__gettext("Manage users")));

		$PAGE->menu_sub[] = array (
			'name' => 'admin:users:banned',
			'html' => a_href(get_url(-1, 'admin::users::banned'),__gettext("Banned users")));

		$PAGE->menu_sub[] = array (
			'name' => 'admin:users:admin',
			'html' => a_href(get_url(-1, 'admin::users::admin'),__gettext("Admin users")));

		$PAGE->menu_sub[] = array (
			'name' => 'admin:flags',
			'html' => a_href(get_url(-1, 'admin::flags'),__gettext("Manage flagged content")));

		$PAGE->menu_sub[] = array (
			'name' => 'admin:spam',
			'html' => a_href(get_url(-1, 'admin::spam'),__gettext("Spam control")));
	}

}
function admin_init() {
	global $CFG, $function;

	// Elgg administration utilities
	// Ben Werdmuller, September 2005

	// These utilities allow users tagged with the 'administration' flag
	// to perform tasks on other users' accounts, including editing posts,
	// banning or deleting accounts, adding accounts in bulk and so on.

	// Permissions check
	// Establishes permissions; if the question is 'does this admin user
	// have permissions', the answer is 'yes'
	$function['permissions:check'][] = $CFG->dirroot . "mod/admin/lib/permissions_check.php";

	// Main admin panel screen
	$function['admin:main'][] = $CFG->dirroot . "mod/admin/lib/admin_main.php";

	// Content flagging system
	$function['profile:view'][] = $CFG->dirroot . "mod/admin/lib/display_content_flag_form.php";
	$function['weblogs:posts:view:individual'][] = $CFG->dirroot . "mod/admin/lib/display_content_flag_form.php";
	$function['files:folder:view'][] = $CFG->dirroot . "mod/admin/lib/display_content_flag_form.php";

	// Content flag administration
	$function['admin:contentflags'][] = $CFG->dirroot . "mod/admin/lib/admin_contentflags.php";

	// Extra administration of user details
	$function['userdetails:edit:details'][] = $CFG->dirroot . "mod/admin/lib/admin_userdetails.php";
	// Menu to view all users
	$function['admin:users'][] = $CFG->dirroot . "mod/admin/lib/admin_users.php";
	$function['admin:users:admin'][] = $CFG->dirroot . "mod/admin/lib/admin_admin_users.php";
	$function['admin:users:banned'][] = $CFG->dirroot . "mod/admin/lib/admin_banned_users.php";

	// Bulk user addition screen
	$function['admin:users:add'][] = $CFG->dirroot . "mod/admin/lib/admin_users_add.php";

	// Display a user control panel when given a database row from elgg.users
	$function['admin:users:panel'][] = $CFG->dirroot . "mod/admin/lib/admin_users_panel.php";

	// Anti-spam
	$function['admin:spam'][] = $CFG->dirroot . "mod/admin/lib/admin_spam.php";
	$function['spam:check'][] = $CFG->dirroot . "mod/admin/lib/spam_check.php";

	// Admin-related actions
    // also allows users to flag content
	$function['init'][] = $CFG->dirroot . "mod/admin/lib/admin_actions.php";

}

function admin_url($oid, $type) {
    global $CFG;
    $url = null;

    if (strpos($type, 'admin::') == 0) {
        $url = $CFG->wwwroot . 'mod/admin/';
        switch ($type) {
            case 'admin::spam':
                $url .= '?do=spam';
                break;
            case 'admin::flags':
                $url .= '?do=flags';
                break;
            case 'admin::users::add':
                $url .= '?do=users&amp;view=add';
                break;
            case 'admin::users::admin':
                $url .= '?do=users&amp;view=admin';
                break;
            case 'admin::users::banned':
                $url .= '?do=users&amp;view=banned';
                break;
            case 'admin::users':
                $url .= '?do=users';
                break;
            case 'admin::userdetails':
                $url = $CFG->wwwroot . 'mod/users/?context=admin&profile_id='.$oid;
                break;
            case 'admin::main':
            default:
                break;
        }
    }

    return $url;
}

function get_url_query($object_id, $object_type, $query) {
    $url = get_url($object_id, $object_type);

    if (strpos($url, '?') === false) {
        $sep = '?';
    } else {
        $sep = '&amp;';
    }

    return $url . $sep . $query;
}

/**
 * Register user types
 */
function register_user_type($type) {
    global $CFG;
    if (!isset($CFG->user_types)) {
        $CFG->user_types = array();
    }

    $CFG->user_types[] = $type;
    return true;
}

function get_user_types() {
    global $CFG;
    if (!isset($CFG->user_types)) {
        $CFG->user_types = array();
    }

    return $CFG->user_types;
}

?>
