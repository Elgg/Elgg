<?php
/**
 * Main navigation bar
 **/
 
echo "<div id='elgg_main_nav' class='clearfloat'>";
echo "<ul class='navigation'>";
//if(get_context() == 'home')
//	$selected = "class=selected";
//else
//	$selected = "";
//echo "<li {$selected}><a href=\"{$vars['url']}\" id='home'><span>Home</span></a></li>";
if(is_plugin_enabled('riverdashboard')){
	if(get_context() == 'riverdashboard')
		$selected = 'class="selected"';
	else
		$selected = "";
	echo "<li {$selected}><a href=\"{$vars['url']}mod/riverdashboard/\" id='activity'><span>Activity</span></a></li>";
}
if(is_plugin_enabled('thewire') && isloggedin()){
	if(get_context() == 'thewire')
		$selected = 'class="selected"';
	else
		$selected = "";
	echo "<li {$selected}><a href=\"{$vars['url']}mod/thewire/all.php\" id='thewire'><span>" . elgg_echo('thewire') . "</span></a></li>";
}
if(is_plugin_enabled('blog')){
	if(get_context() == 'blog')
		$selected = 'class="selected"';
	else
		$selected = "";
	echo "<li {$selected}><a href=\"{$vars['url']}mod/blog/all.php\" id='blogs'><span>Blogs</span></a></li>";
}
if(is_plugin_enabled('pages')){
	if(get_context() == 'pages')
		$selected = 'class="selected"';
	else
		$selected = "";
	echo "<li {$selected}><a href=\"{$vars['url']}mod/pages/all.php\" id='pages'><span>Pages</span></a></li>";
}
if(is_plugin_enabled('file')){
	if(get_context() == 'file')
		$selected = 'class="selected"';
	else
		$selected = "";
	echo "<li {$selected}><a href=\"{$vars['url']}mod/file/all.php\" id='files'><span>Files</span></a></li>";
}
if(is_plugin_enabled('bookmarks')){
	if(get_context() == 'bookmarks')
		$selected = 'class="selected"';
	else
		$selected = "";
	echo "<li {$selected}><a href=\"{$vars['url']}mod/bookmarks/all.php\" id='bookmarks'><span>Bookmarks</span></a></li>";
}
if(is_plugin_enabled('groups')){
	if(get_context() == 'groups')
		$selected = 'class="selected"';
	else
		$selected = "";
	echo "<li {$selected}><a href=\"{$vars['url']}pg/groups/all/\" id='groups'><span>". elgg_echo('groups') . "</span></a></li>";
}
echo "</ul>";
echo "</div>";

?>