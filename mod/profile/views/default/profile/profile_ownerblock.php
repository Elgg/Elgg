<?php

/**
 * A simple owner block which houses info about the user whose 'stuff' you are looking at
 */
 
//get the page owner
if($vars['entity']){
	if($vars['context'] == 'edit')
		$user = get_entity($vars['entity']->container_guid);
	else
		$user = get_entity($vars['entity']->guid);
}else{
	$user = page_owner_entity();
}
$more_info = '';
//set some variables
$location = elgg_view("output/tags",array('value' => $user->location));
$section = $vars['section'];
if($section == 'details'){
	$icon = elgg_view("profile/icon",array('entity' => $user, 'size' => 'large', 'override' => 'true'));
	$icon_class = "large";
}else{
	$icon = elgg_view("profile/icon",array('entity' => $user, 'size' => 'small'));
	$more_info = "<div class='owner_block_contents clearfloat'>";
	$more_info .= "<h3><a href='{$url}'>{$user->name}</a></h3>";
	$more_info .= "<p class='profile_info briefdescription'>{$user->briefdescription}</p>";
	$more_info .= "<p class='profile_info location'>{$location}</p>";
	$more_info .= "</div>";
}
$profile_actions = "";
if(get_loggedin_user()->getGuid() == page_owner()){
	$profile_actions = "<div class='clearfloat profile_actions'>";
	$profile_actions .= "<a href='{$vars['url']}pg/profile/{$user->username}/edit/details' class='action_button'>Edit profile</a>";
	$profile_actions .= "<a href='{$vars['url']}pg/profile/{$user->username}/edit/icon' class='action_button'>Edit profile icon</a>";
	$profile_actions .= "</div>";
}else{
	$profile_actions = "<div class='profile_actions'>";
	if (isloggedin()) {
		if ($_SESSION['user']->getGUID() != $user->getGUID()) {
			$ts = time();
			$token = generate_action_token($ts);
					
			if ($user->isFriend()) {
				$profile_actions .= "<a href=\"{$vars['url']}action/friends/remove?friend={$user->getGUID()}&__elgg_token=$token&__elgg_ts=$ts\" class='action_button'>" . elgg_echo('friend:remove') . "</a>";
			} else {
				$profile_actions .= "<a href=\"{$vars['url']}action/friends/add?friend={$user->getGUID()}&__elgg_token=$token&__elgg_ts=$ts\" class='action_button'>" . elgg_echo('friend:add') . "</a>";
			}
		}
	}
	if(is_plugin_enabled('messages')){
		$profile_actions .= "<a href=\"{$vars['url']}mod/messages/send.php?send_to={$user->guid}\" class='action_button'>". elgg_echo('messages:send') ."</a>";
	}
	$profile_actions .= "</div>";
}

$username = $user->username;
$email = $user->email;
$phone = $user->phone;
	
//get correct links
$url = $vars['url'];

//if admin display admin links
if(isadminloggedin()){
	$admin_links = elgg_view('profile/admin_menu');
}else{
	$admin_links = '';
}


//check tools are enabled
if(is_plugin_enabled('file')){
	$file_link = "<li {$file_highlight}><a href=\"{$vars['url']}pg/file/{$username}\">Files</a></li>";
}else{
	$file_link = "";
}
if(is_plugin_enabled('blog')){
	$blog_link = "<li {$blog_highlight}><a href=\"{$vars['url']}pg/blog/{$username}\">Blog</a></li>";
}else{
	$blog_link = "";
}
if(is_plugin_enabled('video')){
	$video_link = "<li {$video_highlight}><a href=\"{$vars['url']}pg/video/{$username}\">Videos</a></li>";
}else{
	$video_link = "";
}
if(is_plugin_enabled('pages')){
	$pages_link = "<li {$pages_highlight}><a href=\"{$vars['url']}pg/pages/owned/{$username}\">Pages</a></li>";
}else{
	$pages_link = "";
}
if(is_plugin_enabled('bookmarks')){
	$bookmark_link = "<li {$bookmarks_highlight}><a href=\"{$vars['url']}pg/bookmarks/{$username}\">Bookmarks</a></li>";
}else{
	$bookmark_link = "";
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
		{$file_link}
		{$blog_link}
		{$video_link}
		{$bookmark_link}
		{$pages_link}
		</ul>
	</div>
	<!-- if admin user -->
	{$admin_links}	
</div>
	
EOT;

echo $display;
