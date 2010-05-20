<?php
/**
 * Displays the Add New button, and the All, Mine, My Friends tabs for plugins
 * If a user is not logged in, this only displays the All tab.
 * If this is in a group context, it doesn't display any tabs
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 */

$page_owner = page_owner_entity();
$logged_in_user = get_loggedin_user();
$username = $logged_in_user->username;

if (!$page_owner) {
	$page_owner = $logged_in_user;
}

// so we know if the user is looking at their own, everyone's or all friends
$filter_context = $vars['context'];

// get the object type
$type = $vars['type'];

// tons of empty strings.
$mine_selected = $all_selected = $friend_selected =
$new_button = $action_buttons = $filter_content = '';

$all_title = elgg_echo('all');
$mine_title = elgg_echo('mine');
$friend_title = elgg_echo('friends');

if (!($page_owner instanceof ElggGroup)){
	if($filter_context == 'everyone') {
		$all_selected = "class = 'selected'";
	}
	if($filter_context == 'mine') {
		$mine_selected = "class = 'selected'";
	}
	if($filter_context == 'friends') {
		$friend_selected = "class = 'selected'";
	}
}

// allow plugins to override default page handlers
// @todo switch this over to proper page handling style
$all_link = (isset($vars['all_link'])) ? $vars['all_link'] : "{$vars['url']}mod/$type/all.php";
$mine_link = (isset($vars['mine_link'])) ? $vars['mine_link'] : "{$vars['url']}pg/$type/$username";
$friend_link = (isset($vars['friend_link'])) ? $vars['friend_link'] : "{$vars['url']}pg/$type/$username/friends";
$new_link = (isset($vars['new_link'])) ? $vars['new_link'] : "{$CONFIG->wwwroot}pg/$type/$username/new";

$title = elgg_echo($type);
$title = '<div class="content_header_title">' . elgg_view_title($title) . '</div>';

$tabs = <<<EOT
	<div class="elgg_horizontal_tabbed_nav margin_top">
		<ul>
			<li $all_selected><a href="$all_link">$all_title</a></li>
			<li $mine_selected><a href="$mine_link">$mine_title</a></li>
			<li $friend_selected><a href="$friend_link">$friend_title</a></li>
		</ul>
	</div>
EOT;

// must be logged in to see the filter menu and any action buttons
if (isloggedin()) {
	// only show the new button when not on the add form.
	// hide the tabs when on the add form.
	if ($filter_context == 'action') {
		$tabs = '';
	} else {
		$new_button = "<a href=\"{$new_link}\" class='action_button'>" . elgg_echo($type . ':new') . '</a>';
		if(get_context() == "videolist"){
			$video_link = $CONFIG->wwwroot . "pg/videolist/browse/$username/";
			$browse_video .= "<a href=\"{$video_link}\" class='action_button'>" . elgg_echo('videolist:browsemenu') . '</a>';
		}else{
			$browse_video = '';
		}
		$new_button = "<div class='content_header_options'>$new_button $browse_video</div>";
	}

	// also hide the tabs if in a group context (ie, listing groups) or
	// when viewing tools belonging to a group
	if (get_context() == 'groups' || $page_owner instanceof ElggGroup) {
		$tabs = '';
	}
} else {
	// only show logged out users the all tab
	$page_filter = <<<EOT
		<div class="elgg_horizontal_tabbed_nav margin_top">
			<ul>
				<li $all_selected><a href="$all_link">$all_title</a></li>
			</ul>
		</div>
EOT;
}
?>
<div id="content_header" class="clearfloat">
	<?php echo $title . $new_button; ?>
</div>
<?php echo $tabs; ?>

