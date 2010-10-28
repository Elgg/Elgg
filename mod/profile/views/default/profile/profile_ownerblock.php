<?php

/**
 * A simple owner block which houses info about the user whose 'stuff' you are looking at
 */

// get the user who owns this profile
if ($vars['entity']) {
	if ($vars['context'] == 'edit') {
		$user = get_entity($vars['entity']->container_guid);
	} else {
		$user = get_entity($vars['entity']->guid);
	}
} else {
	$user = page_owner_entity();
}
if (!$user) {
	// no user so we quit view
	echo sprintf(elgg_echo('viewfailure'), __FILE__);
	return TRUE;
}

$more_info = '';

$location = elgg_view("output/tags",array('value' => $user->location));
$section = $vars['section'];
if ($section == 'details') {
	$icon = elgg_view("profile/icon",array('entity' => $user, 'size' => 'large', 'override' => 'true'));
	$icon_class = "large";
} else {
	$icon = elgg_view("profile/icon",array('entity' => $user, 'size' => 'small'));
	$more_info = "<div class='owner_block_contents clearfloat'>";
	$more_info .= "<h3><a href='{$url}'>{$user->name}</a></h3>";
	$more_info .= "<p class='profile_info briefdescription'>{$user->briefdescription}</p>";
	$more_info .= "<p class='profile_info location'>{$location}</p>";
	$more_info .= "</div>";
}
$profile_actions = "";
if (isloggedin() && (get_loggedin_user()->getGuid() == page_owner())) {
	$profile_actions = "<div class='clearfloat profile_actions'>";
	$profile_actions .= "<a href='{$vars['url']}pg/profile/{$user->username}/edit/details' class='action_button'>". elgg_echo('profile:edit') ."</a>";
	$profile_actions .= "<a href='{$vars['url']}pg/profile/{$user->username}/edit/icon' class='action_button'>". elgg_echo('profile:editicon') ."</a>";
	$profile_actions .= "</div>";
} else {
	$profile_actions = "<div class='profile_actions'>";
	if (isloggedin()) {
		if (get_loggedin_userid() != $user->getGUID()) {
			if ($user->isFriend()) {
				$url = "{$vars['url']}action/friends/remove?friend={$user->getGUID()}";
				$url = elgg_add_action_tokens_to_url($url);
				$profile_actions .= "<a href=\"$url\" class='action_button'>" . elgg_echo('friend:remove') . "</a>";
			} else {
				$url = "{$vars['url']}action/friends/add?friend={$user->getGUID()}";
				$url = elgg_add_action_tokens_to_url($url);
				$profile_actions .= "<a href=\"$url\" class='action_button'>" . elgg_echo('friend:add') . "</a>";
			}
		}
	}
	if (is_plugin_enabled('messages') && isloggedin()) {
		$profile_actions .= "<a href=\"{$vars['url']}mod/messages/send.php?send_to={$user->guid}\" class='action_button'>". elgg_echo('messages:send') ."</a>";
	}
	$profile_actions .= "</div>";
}

$username = $user->username;
$email = $user->email;
$phone = $user->phone;


//if admin display admin links
if (isadminloggedin()) {
	$admin_links = elgg_view('profile/admin_menu');
} else {
	$admin_links = '';
}


//check tools are enabled - hard-coded for phase1
// @todo - provide a view to extend for profile pages ownerblock tool-links
if(is_plugin_enabled('blog')){
	$blog_link = "<li><a href=\"{$vars['url']}pg/blog/{$username}\">Blog</a></li>";
}else{
	$blog_link = "";
}
if(is_plugin_enabled('bookmarks')){
	$bookmark_link = "<li><a href=\"{$vars['url']}pg/bookmarks/{$username}\">Bookmarks</a></li>";
}else{
	$bookmark_link = "";
}
if(is_plugin_enabled('document')){
	$docs_link = "<li><a href=\"{$vars['url']}pg/document/{$username}\">Documents</a></li>";
}else{
	$docs_link = "";
}
if(is_plugin_enabled('feeds')){
	$feeds_link = "<li><a href=\"{$vars['url']}pg/feeds/{$username}\">Feeds</a></li>";
}else{
	$feeds_link = "";
}
if(is_plugin_enabled('tidypics')){
	$tidypics_link = "<li><a href=\"{$vars['url']}pg/photos/owned/{$username}\">Photos</a></li>";
}else{
	$tidypics_link = "";
}
if(is_plugin_enabled('videolist')){
	$video_link = "<li><a href=\"{$vars['url']}pg/videolist/owned/{$username}\">Videos</a></li>";
}else{
	$video_link = "";
}

//contruct the display
$display = <<<EOT

<div id="owner_block">
	<div class="owner_block_icon {$icon_class}">
		{$icon}
	</div>
	{$more_info}
	{$profile_actions}
	<div class="owner_block_links">
		<ul>
		{$blog_link}
		{$bookmark_link}
		{$docs_link}
		{$feeds_link}
		{$tidypics_link}
		{$video_link}
		</ul>
	</div>
	<!-- if admin user -->
	{$admin_links}
</div>

EOT;

echo $display;
