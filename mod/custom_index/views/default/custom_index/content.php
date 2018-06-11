<?php
/**
 * Elgg custom index layout
 *
 * This is just a helper view to make it easier to use Elgg's
 * page-rendering helper functions like elgg_view_page.
 */

$list_params = [
	'type' => 'object',
	'limit' => 4,
	'list_type_toggle' => false,
	'pagination' => false,
	'no_results' => true,
];

?>

<div class="custom-index elgg-main elgg-grid clearfix">
	<div class="elgg-col elgg-col-1of2 custom-index-col1">
		<div class="elgg-inner">
<?php
// left column

// Top box for login or welcome message
if (elgg_is_logged_in()) {
	$content = elgg_format_element('h2', [], elgg_echo('welcome') . ' ' . elgg_get_logged_in_user_entity()->getDisplayName());
	
	echo elgg_view_module('featured', '', $content);
} else {
	echo elgg_view_module('featured', elgg_echo('login'), elgg_view_form('login'));
}

// a view for plugins to extend
echo elgg_view('index/lefthandside');

// files
if (elgg_is_active_plugin('file')) {
	$file_params = $list_params;
	$file_params['subtype'] = 'file';
	echo elgg_view_module('featured',  elgg_echo('collection:object:file'), elgg_list_entities($file_params));
}

// groups
if (elgg_is_active_plugin('groups')) {
	$group_params = $list_params;
	$group_params['type'] = 'group';
	echo elgg_view_module('featured',  elgg_echo('collection:group'), elgg_list_entities($group_params));
}
?>
		</div>
	</div>
	<div class="elgg-col elgg-col-1of2 custom-index-col2">
		<div class="elgg-inner">
<?php
// right column

// a view for plugins to extend
echo elgg_view("index/righthandside");

$newest_members = elgg_list_entities([
	'type' => 'user',
	'limit' => 10,
	'pagination' => false,
	'no_results' => true,
]);
echo elgg_view_module('featured',  elgg_echo('collection:user'), $newest_members);

// groups
if (elgg_is_active_plugin('blog')) {
	$blog_params = $list_params;
	$blog_params['subtype'] = 'blog';
	echo elgg_view_module('featured',  elgg_echo('collection:object:blog'), elgg_list_entities($blog_params));
}

// files
if (elgg_is_active_plugin('bookmarks')) {
	$bookmarks_params = $list_params;
	$bookmarks_params['subtype'] = 'bookmarks';
	echo elgg_view_module('featured',  elgg_echo('collection:object:bookmarks'), elgg_list_entities($bookmarks_params));
}
?>
		</div>
	</div>
</div>
