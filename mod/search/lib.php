<?php
/*
 * Created on Sep 19, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2007
 */
function search_pagesetup() {

}
function search_init() {
	global $CFG,$function;
	// ELGG Keyword search system

	// Parse REQUEST field
	$function['search:display'][] = $CFG->dirroot . "mod/search/lib/function_display.php";
	$function['search:all:display'][] = $CFG->dirroot . "mod/search/lib/function_search_all_display.php";
	$function['search:all:display:rss'][] = $CFG->dirroot . "mod/search/lib/function_search_all_display_rss.php";
	$function['search:tags:display'][] = $CFG->dirroot . "mod/search/lib/tags_display.php";
	$function['search:tags:personal:display'][] = $CFG->dirroot . "mod/search/lib/tags_display_personal.php";

	// Suggest tags
	$function['search:tags:suggest'][] = $CFG->dirroot . "mod/search/lib/search_suggest_tags.php";

	// Suggest users
	$function['search:users:suggest'][] = $CFG->dirroot . "mod/search/lib/search_suggest_users.php";

	// Suggest RSS
	$function['search:rss:suggest'][] = $CFG->dirroot . "mod/search/lib/search_suggest_rss.php";

	// Log on bar down the right hand side
	// $function['display:sidebar'][] = $CFG->dirroot . "mod/search/lib/search_user_info_menu.php";

	// Actions to perform when an access group is deleted
	$function['groups:delete'][] = $CFG->dirroot . "mod/search/lib/groups_delete.php";

}
?>
