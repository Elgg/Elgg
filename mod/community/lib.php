<?php
// Communities module

/*
A brief explanation:

Communities are a specialisation of users. Each community is just another
row in the users table, albeit with user_type set to 'community', which
allows it to have all the features of a regular user.

Friendships are stored in the same way too, but displayed as memberships.
The 'owner' field of the users table stores the moderator for a community
(for regular users it's set to -1).

TO DO:

  - Allow a moderator to restrict access to communities
  - Allow moderators to delete all weblog postings and file uploads

*/

function community_pagesetup() {
    // register links --
    global $profile_id;
    global $PAGE;
    global $CFG;
    global $USER;
    global $metatags;

    require_once (dirname(__FILE__)."/default_template.php");
    require_once (dirname(__FILE__)."/lib/communities_config.php");

    $metatags .= "<link rel=\"stylesheet\" href=\"" . $CFG->wwwroot . "mod/community/css.css\" type=\"text/css\" media=\"screen\" />";

    $page_owner = $profile_id;

    $usertype = user_type($page_owner);

    $username= user_info('username', $page_owner);

    if ($usertype == "community") {

        if (defined("context") && context == "profile") {

            if (run("permissions:check", "profile")) {

                // Edit community functions
                $PAGE->menu_sub[] = array( 'name' => 'profile:edit',
                                       'html' => '<a href="'.$CFG->wwwroot.'profile/edit.php?profile_id='.$page_owner.'">'
                                       . __gettext("Edit community profile") . '</a>');

                $PAGE->menu_sub[] = array( 'name' => 'community:pic',
                                           'html' => a_href("{$CFG->wwwroot}_icons/?context=profile&amp;profile_id=$page_owner" ,
                                                              __gettext("Community site picture")));

                $PAGE->menu_sub[] = array( 'name' => 'community:edit',
                                           'html' => a_href("{$CFG->wwwroot}_userdetails/?context=profile&amp;profile_id=$page_owner" ,
                                                             __gettext("Edit community details")));
            }

        }

        if (defined("context") && (context == "profile" || context == COMMUNITY_CONTEXT)) {
          if (run("permissions:check", "profile")) {
              if(context == COMMUNITY_CONTEXT){
              $PAGE->menu_sub[] = array( 'name' => 'profile:view',
                                         'html' => a_href("{$CFG->wwwroot}{$username}/profile",
                                         __gettext("Return to community profile")));
              }

              $PAGE->menu_sub[] = array( 'name' => 'community:adminmembers',
                                         'html' => a_href("{$CFG->wwwroot}{$username}/community/members",
                                         __gettext("Edit members")));

              $PAGE->menu_sub[] = array( 'name' => 'community:requests',
                                         'html' => a_href("{$CFG->wwwroot}{$username}/community/requests",
                                                           __gettext("View membership requests")));
            }
        }
        
        if (defined("context") && context == "profile") {

            if (run("permissions:check", "profile")) {
                
                if (!empty($CFG->uses_YUI)) {
                    $PAGE->menu_sub[] = array( 'name' => 'profile:widget:manage',
                        'html' => '<a href="'.$CFG->wwwroot.'mod/widget/manage_widgets.php?owner='.$page_owner.'">'
                        . __gettext("Manage widgets") . '</a>');
                } else {
                    $PAGE->menu_sub[] = array( 'name' => 'profile:widget:add',
                        'html' => '<a href="'.$CFG->wwwroot.'mod/profile/add.php?owner='.$page_owner.'">'
                        . __gettext("Add widget") . '</a>');
                
            }
    
            }
        }
        
    } else if ($usertype == "person") {

        if (defined("context") && context == COMMUNITY_CONTEXT) {
          if(COMMUNITY_COMPACT_VIEW){
            $PAGE->menu_sub[] = array( 'name' => 'community',
                                     'html' => a_href("{$CFG->wwwroot}{$username}/communities" ,
                                                        __gettext("Communities")));

            if (logged_on && $page_owner == $_SESSION['userid'] &&
                ($CFG->community_create_flag == "" || user_flag_get($CFG->community_create_flag, $USER->ident))) {
              $PAGE->menu_sub[] = array( 'name' => 'community:owned',
                                     'html' => a_href("{$CFG->wwwroot}{$username}/communities/new" ,
                                                        __gettext("New Community")));
            }
          }
          else{
            $PAGE->menu_sub[] = array( 'name' => 'community',
                                     'html' => a_href("{$CFG->wwwroot}{$username}/communities" ,
                                                        __gettext("Communities")));

            if ($CFG->community_create_flag == "" || user_flag_get($CFG->community_create_flag, $USER->ident)) {
              $PAGE->menu_sub[] = array( 'name' => 'community:owned',
                                     'html' => a_href("{$CFG->wwwroot}{$username}/communities/owned" ,
                                                        __gettext("Owned Communities")));
            }
          }
        }
    }

    $PAGE->search_menu[] = array( 'name' => __gettext("Communities"),
                                  'user_type' => 'community');

}

function community_init() {
        global $CFG,$function;

    // Add communities to access levels
        include($CFG->dirroot . "mod/community/lib/communities_access_levels.php");
        $function['userdetails:init'][] = $CFG->dirroot . "mod/community/lib/userdetails_actions.php";

    // Communities actions
        $function['communities:init'][] = $CFG->dirroot . "mod/community/lib/communities_config.php";
        $function['communities:init'][] = $CFG->dirroot . "mod/community/lib/communities_actions.php";

    // Communities modifications of friends actions
        //$function['friends:init'][] = $CFG->dirroot . "mod/community/lib/communities_actions.php";

    // Communities bar down the right hand side
        $function['display:sidebar'][] = $CFG->dirroot . "mod/community/lib/communities_owned.php";
        $function['display:sidebar'][] = $CFG->dirroot . "mod/community/lib/community_memberships.php";

    // 'Communities' aspect to the little menus beneath peoples' icons
        $function['users:infobox:menu:text'][] = $CFG->dirroot . "mod/community/lib/user_info_menu_text.php";

    // Permissions for communities
        $function['permissions:check'][] = $CFG->dirroot . "mod/community/lib/permissions_check.php";

    // View community memberships
        $function['communities:editpage'][] = $CFG->dirroot . "mod/community/lib/communities_edit_wrapper.php";
        $function['communities:edit'][] = $CFG->dirroot . "mod/community/lib/communities_edit.php";
        $function['communities:members'][] = $CFG->dirroot . "mod/community/lib/communities_members.php";
        $function['communities:owned'][] = $CFG->dirroot . "mod/community/lib/communities_moderator_of.php";
        $function['communities:owned'][] = $CFG->dirroot . "mod/community/lib/communities_create.php";
        $function['communities:create'][] = $CFG->dirroot . "mod/community/lib/communities_create.php";

    // Membership requests
        $function['communities:requests:view'][] = $CFG->dirroot . "mod/community/lib/communities_membership_requests.php";

    // Check access levels
        $function['users:access_level_check'][] = $CFG->dirroot . "mod/community/lib/communities_access_level_check.php";

    // Obtain SQL "where" string for access levels
        $function['users:access_level_sql_where'][] = $CFG->dirroot . "mod/community/lib/communities_access_level_sql_check.php";

    // Link to edit icons
        $function['profile:edit:link'][] = $CFG->dirroot . "mod/community/lib/profile_edit_link.php";

    // Edit profile details
        $function['userdetails:edit'][] = $CFG->dirroot . "mod/community/lib/userdetails_edit.php";

    // Delete users
        listen_for_event("user","delete","community_user_delete");
        
    // Register file river hook (if there)
    	if (function_exists('river_save_event'))
    	{
    		listen_for_event('community','publish', 'community_river_hook');
    		listen_for_event('community','delete', 'community_river_hook');
    
    		river_register_friendlyname_hook('community::community', 'community_get_friendly_name');
    	}
        
}

function community_get_friendly_name($object_type, $object_id)
{
	global $CFG;

	if ($object_type == 'community::community')
	{
		$record = get_record_sql("SELECT * from {$CFG->prefix}users where ident=$object_id and user_type = 'community'");

		if ($record)
		{
			$community = user_info("name", $record->ident);
			$url = river_get_userurl($record->ident);
			
			return sprintf(__gettext("the community <a href=\"$url\">%s</a>"), $community);
		}
	}

	return "";
}

function community_river_hook( $object_type, $event, $object)
{
	global $CFG;

	$userid = ($_SESSION['userid'] == "" ? -1 : $_SESSION['userid']);
	$object_id = $object->ident;
	$object_owner = $object->owner;
	$title = trim($object->name);

	$username = user_info("name", $userid);
	$weblogname = "<a href=\"" . river_get_userurl($userid) . "\">". user_info("name", $object_id) . "</a>'s";
	if ($userid == $object_owner) $weblogname = __gettext("their");

	if ($username == false) $username = __gettext("Anonymous user");
	
	if ($event == "publish")
		river_save_event($userid, $object_id, $object_owner, $object_type, "<a href=\"" .  river_get_userurl($userid) . "\">$username</a> created the community <a href=\"{$CFG->wwwroot}{$object->username}\">{$object->name}</a>.");

	return $object;
}

function community_user_delete($object_type, $event, $object) {
    global $CFG, $data, $messages;
    if (!empty($object->ident) && $object_type == "user" && $event == "delete") {
        if ($newsuser = user_info_username("info","news")) {
        } else {
            $newsuser = -1;
        }

        if ($communities = get_records_sql("select * from {$CFG->prefix}users where owner = {$object->ident}")) {
            foreach($communities as $community) {
                $community->owner = $newsuser;
                update_record('users',$community);
                if ($newsuser != -1) {
                    $messages[] = sprintf(__gettext("Community %s was returned to the news user."),$community->username);
                } else {
                    $messages[] = sprintf(__gettext("Community %s is now owned by -1 (nobody)."),$community->username);
                }
            }
        }
    }
//          $members = get_records("friends","friend",$page_owner);

    return $object;
}


?>
