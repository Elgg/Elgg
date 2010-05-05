<?php
/**
 * Elgg admin sidebar
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$sections = $vars['config']->admin_sections;
$current_section = $vars['page'][0];
$child_section = (isset($vars['page'][1])) ? $vars['page'][1] : NULL;

// "Plugin Settings" is a special sidemenu item that is added automatically
// it's calculated here instead of in admin_init() because of preformance concerns.
$installed_plugins = get_installed_plugins();
$plugin_settings_children = $sort = array();
foreach ($installed_plugins as $plugin_id => $info) {
	if (!$info['active']) {
		continue;
	}

	// @todo might not need to check if plugin is enabled here because
	// this view wouldn't exist if it's not.  right?
	if (is_plugin_enabled($plugin_id) && elgg_view_exists("settings/{$plugin_id}/edit")) {
		$plugin_settings_children[$plugin_id] = array(
			'title' => $info['manifest']['name']
		);
		$sort[] = elgg_strtolower($info['manifest']['name']);
	}
}

array_multisort($sort, SORT_ASC, SORT_STRING, $plugin_settings_children);

if ($plugin_settings_children) {
	// merge in legacy support with new support.
	if (!isset($sections['plugin_settings'])) {
		$sections['plugin_settings'] = array(
			'title' => elgg_echo('admin:plugin_settings'),
			'children' => $plugin_settings_children
		);
	} else {
		$sections['plugin_settings']['title'] = elgg_echo('admin:plugin_settings');
		if (isset($sections['plugin_settings']['children'])) {
			$children = array_merge($plugin_settings_children, $sections['plugin_settings']['children']);
			$sections['plugin_settings']['children'] = $children;
		}
	}
}

?>

<ul class="admin submenu">
	<?php foreach ($sections as $id => $info) {
		$parent_class = ($current_section == $id) ? 'selected' : '';
		$link = "{$vars['url']}pg/admin/$id";

		$expand_child = $children_menu = $expanded = '';
		// parent menu items with children default to the first child element.
		if (isset($info['children']) && $info['children']) {
			$link = '';
			if ($current_section == $id) {
				$hidden = '';
				$expanded = '-';
			} else {
				$hidden = 'style="display: none;"';
				$expanded = '+';
			}
			$expand_child = "<span class=\"expand_child\">$expanded</span> ";
			$children_menu = "<ul class=\"admin child_submenu\" $hidden>";
			foreach ($info['children'] as $child_id => $child_info) {
				$child_selected = ($child_section == $child_id) ? "class=\"selected\"" : '';
				$child_link = "{$vars['url']}pg/admin/$id/$child_id";
				if (!$link) {
					$link = $child_link;
				}
				$children_menu .= "<li $child_selected><a href=\"$child_link\">{$child_info['title']}</a></li>";
			}
			$children_menu .= '</ul>';
		}

		$parent_class = ($parent_class) ? "class=\"$parent_class\"" : '';

		echo "<li $parent_class><a href=\"$link\">$expand_child{$info['title']}</a>
		$children_menu
		</li>";
	}
	?>
</ul>

<script type="text/javascript">
	$('a span.expand_child').click(function() {
		var submenu = $(this).parent().parent().find('ul.child_submenu');
		submenu.slideToggle();

		if ($(this).html() == '+') {
			$(this).html('-');
		} else {
			$(this).html('+');
		}

		return false;
	});
</script>