<?php
/**
 * Displays the dropdown filter menu.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 */

global $CONFIG;

// set variables
$page_owner = page_owner_entity();
if (!$page_owner) {
	$page_owner = get_loggedin_user();
}
$filter_context = $vars['context']; // so we know if the user is looking at their own, everyone's or all friends
$type = $vars['type']; // get the object type
$mine_selected = '';
$all_selected = '';
$friend_selected = '';
$action_buttons = '';

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
	if($filter_context == 'action') {
		// if this is an action page
	}
}

// must be logged in to see the filter menu and any action buttons
if ( isloggedin() ) {
	// if we're not on an action page (add a bookmark, create a blog, upload a file etc), or a group page 
	if ( ($filter_context != 'action') && (get_context() != 'groups') ) {
		$title = elgg_echo($type);
		$title = "<div class='content_header_title'>".elgg_view_title($title)."</div>";
		$page_filter = <<<EOT
			<div class="elgg_horizontal_tabbed_nav margin_top">
				<ul>
					<li {$all_selected}><a href="{$vars['url']}mod/{$type}/all.php">{$all_title}</a></li>
					<li {$mine_selected}><a href="{$vars['url']}pg/{$type}/{$_SESSION['user']->username}">{$mine_title}</a></li>
					<li {$friend_selected}><a href="{$vars['url']}pg/{$type}/{$_SESSION['user']->username}/friends/">{$friend_title}</a></li>
				</ul>
			</div>		
EOT;
		// action buttons
		if(get_context() != 'bookmarks'){
			$url = $CONFIG->wwwroot . "pg/{$type}/". $page_owner->username . "/new";
		} else {
			$url = $CONFIG->wwwroot . "pg/{$type}/". $page_owner->username . "/add";
		}
		$action_buttons = "<a href=\"{$url}\" class='action_button'>" . elgg_echo($type . ':new') . "</a>";
		$action_buttons = "<div class='content_header_options'>".$action_buttons."</div>";

	} else { // we're on an action page (or groups) - we'll just have a simple page title, and no filter menu
		$title = elgg_echo($type);
		$title = "<div class='content_header_title'>".elgg_view_title( $title )."</div>";
		$page_filter = '';
	}
}
?>
<!-- construct the page content area header -->
<div id="content_header" class="clearfloat">
	<?php echo $title . $action_buttons; ?>
</div>
<?php echo $page_filter; ?>

