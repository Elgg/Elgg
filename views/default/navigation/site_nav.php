<?php
/**
 * Main site-wide navigation
 **/
 
echo "<div id='elgg_main_nav' class='clearfloat'>";
echo "<ul class='navigation'>";

if(is_plugin_enabled('riverdashboard')){
	if(get_context() == 'riverdashboard')
		$selected = 'class="selected"';
	else
		$selected = "";
	echo "<li {$selected}><a href=\"{$vars['url']}mod/riverdashboard/\" title='Activity'><span>Activity</span></a></li>";
}
if(is_plugin_enabled('thewire') && isloggedin()){
	if(get_context() == 'thewire')
		$selected = 'class="selected"';
	else
		$selected = "";
	echo "<li {$selected}><a href=\"{$vars['url']}mod/thewire/everyone.php\" title='The Wire'><span>" . elgg_echo('thewire:title') . "</span></a></li>";
}
if(is_plugin_enabled('conversations') && isloggedin()){
	if(get_context() == 'conversations')
		$selected = 'class="selected"';
	else
		$selected = "";
	echo "<li {$selected}><a href=\"{$vars['url']}mod/conversations/all.php\" title='Conversations'><span>" . elgg_echo('conversations') . "</span></a></li>";
}
if(is_plugin_enabled('blog')){
	if(get_context() == 'blog')
		$selected = 'class="selected"';
	else
		$selected = "";
	echo "<li {$selected}><a href=\"{$vars['url']}mod/blog/all.php\" title='Blogs'><span>Blogs</span></a></li>";
}
if(is_plugin_enabled('pages')){
	if(get_context() == 'pages')
		$selected = 'class="selected"';
	else
		$selected = "";
	echo "<li {$selected}><a href=\"{$vars['url']}mod/pages/all.php\" title='Pages'><span>Pages</span></a></li>";
}
if(is_plugin_enabled('file')){
	if(get_context() == 'file')
		$selected = 'class="selected"';
	else
		$selected = "";
	echo "<li {$selected}><a href=\"{$vars['url']}pg/file/world/world/\" title='Files'><span>Files</span></a></li>";
}
if(is_plugin_enabled('bookmarks')){
	if(get_context() == 'bookmarks')
		$selected = 'class="selected"';
	else
		$selected = "";
	echo "<li {$selected}><a href=\"{$vars['url']}mod/bookmarks/all.php\" title='Bookmarks'><span>Bookmarks</span></a></li>";
}
if(is_plugin_enabled('groups')){
	if(get_context() == 'groups')
		$selected = 'class="selected"';
	else
		$selected = "";
	echo "<li {$selected}><a href=\"{$vars['url']}pg/groups/world/\" title='Groups'><span>". elgg_echo('groups') . "</span></a></li>";
}
echo "</ul>";
echo "</div>";

?>