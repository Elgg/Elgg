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
	$user = elgg_get_page_owner();
}
if (!$user) {
	// no user so we quit view
	echo elgg_echo('viewfailure', array(__FILE__));
	return TRUE;
}

$more_info = '';

$location = elgg_view("output/tags",array('value' => $user->location));

$icon = elgg_view("profile/icon",array('entity' => $user, 'size' => 'large', 'override' => 'true'));
$icon_class = "large";

// @todo pull out into menu
$profile_actions = "";
if (isloggedin() && (get_loggedin_userid() == elgg_get_page_owner_guid())) {
	$profile_actions = "<div class='clearfix profile_actions'>";
	$profile_actions .= "<a href='".elgg_get_site_url()."pg/profile/{$user->username}/edit/details' class='elgg-action-button'>". elgg_echo('profile:edit') ."</a>";
	$profile_actions .= "<a href='".elgg_get_site_url()."pg/avatar/edit/{$user->username}' class='elgg-action-button'>". elgg_echo('avatar:edit') ."</a>";
	$profile_actions .= "</div>";
} else {
	$profile_actions = "<div class='profile_actions'>";
	if (isloggedin()) {
		if (get_loggedin_userid() != $user->getGUID()) {
			if ($user->isFriend()) {
				$url = elgg_get_site_url()."action/friends/remove?friend={$user->getGUID()}";
				$url = elgg_add_action_tokens_to_url($url);
				$profile_actions .= "<a href=\"$url\" class='elgg-action-button'>" . elgg_echo('friend:remove') . "</a>";
			} else {
				$url = elgg_get_site_url()."action/friends/add?friend={$user->getGUID()}";
				$url = elgg_add_action_tokens_to_url($url);
				$profile_actions .= "<a href=\"$url\" class='elgg-action-button'>" . elgg_echo('friend:add') . "</a>";
			}
		}
	}
	if (is_plugin_enabled('messages') && isloggedin()) {
		$profile_actions .= "<a href=\"".elgg_get_site_url()."mod/messages/send.php?send_to={$user->guid}\" class='elgg-action-button'>". elgg_echo('messages:send') ."</a>";
	}
	$profile_actions .= "</div>";
}


// if admin, display admin links
$admin_links = '';
if (isadminloggedin() && get_loggedin_userid() != elgg_get_page_owner_guid()) {
	$params = array(
		'user' => elgg_get_page_owner(),
		'toggle' => true,
		'sort_by' => 'order',
	);
	$admin_links = elgg_view_menu('user_admin', $params);
	$admin_links = "<div class=\"owner_block_links\">$admin_links</div>";
}

// content links
$menu = elgg_view_menu('user_ownerblock', array('user' => elgg_get_page_owner()));

//contruct the display
$display = <<<EOT

<div id="owner_block">
	<div class="owner_block_icon {$icon_class}">
		{$icon}
	</div>
	{$more_info}
	{$profile_actions}
	<div class="owner_block_links">
		$menu
	</div>
	<!-- if admin user -->
	{$admin_links}
</div>

EOT;

echo $display;
