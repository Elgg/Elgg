<?php
/**
 * Elgg administration advanced plugin screen
 *
 * Shows a list of all plugins sorted by load order.
 *
 * @package Elgg.Core
 * @subpackage Admin.Plugins
 */

elgg_generate_plugin_entities();
$installed_plugins = elgg_get_plugins('any');
$show_category = get_input('category', null);

// Get a list of the all categories
// and trim down the plugin list if we're not viewing all categories.
// @todo this could be cached somewhere after have the manifest loaded
$categories = array();

foreach ($installed_plugins as $plugin) {
	if (!$plugin->isValid()) {
		continue;
	}

	$plugin_categories = $plugin->manifest->getCategories();

	// handle plugins that don't declare categories
	// unset them here because this is the list we foreach
	if ($show_category && !in_array($show_category, $plugin_categories)) {
		unset($installed_plugins[$id]);
	}

	if (isset($plugin_categories)) {
		foreach ($plugin_categories as $category) {
			if (!array_key_exists($category, $categories)) {
				$categories[$category] = elgg_echo("admin:plugins:label:moreinfo:categories:$category");
			}
		}
	}
}

$ts = time();
$token = generate_action_token($ts);
$categories = array_merge(array('' => elgg_echo('admin:plugins:categories:all')), $categories);

$category_dropdown = elgg_view('input/dropdown', array(
	'internalname' => 'category',
	'options_values' => $categories,
	'value' => $show_category
));

$category_button = elgg_view('input/submit', array(
	'value' => elgg_echo('filter'),
	'class' => 'elgg-action-button'
));

$category_form = elgg_view('input/form', array(
	'body' => $category_dropdown . $category_button
));

// @todo Until "en/deactivate all" means "All plugins on this page" hide when not looking at all.
if (!isset($show_category) || empty($show_category)) {
	$activate_url = "{$CONFIG->url}action/admin/plugins/activate_all?__elgg_token=$token&amp;__elgg_ts=$ts";
	$deactivate_url = "{$CONFIG->url}action/admin/plugins/deactivate_all?__elgg_token=$token&amp;__elgg_ts=$ts";

	$buttons = "<a class='elgg-action-button' href=\"$activate_url\">" . elgg_echo('admin:plugins:activate_all') . '</a> ';
	$buttons .=	"<a class='elgg-action-button disabled' href=\"$deactivate_url\">" . elgg_echo('admin:plugins:deactivate_all') . '</a> ';
	$buttons .= "<br /><br />";
} else {
	$buttons = '';
}

$buttons .= $category_form;

// construct page header
?>
<div id="content_header" class="clearfix">
	<div class="content-header-options"><?php echo $buttons ?></div>
</div>
<br />
<?php

// Display list of plugins
foreach ($installed_plugins as $plugin) {
	$view = ($plugin->isValid()) ? 'admin/components/plugin' : 'admin/components/invalid_plugin';
	echo elgg_view($view, array(
		'plugin' => $plugin
	));
}
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('a.manifest_details.link').click(function() {
			elgg_slide_toggle($(this), '.plugin_details', '.manifest_file');
		});


		$('.elgg-plugin-screenshot a').click(function(e) {
			e.preventDefault();
			var lb = $('.elgg-plugin-screenshot-lightbox');

			if (lb.length < 1) {
				$('body').append('<div class="elgg-plugin-screenshot-lightbox"></div>');
				lb = $('.elgg-plugin-screenshot-lightbox');

				lb.click(function() {
					lb.hide();
				});

				$(document).click(function(e) {
					var target = $(e.target);
					if (target.is('a') && target.hasClass('elgg-plugin-screenshot-lightbox')) {
						lb.hide();
						e.preventDefault();
					}
				});
			}

			var img = '<img class="pas" src="' + $(this).attr('href') + '">';
			lb.html(img);

			top_pos = $(window).scrollTop() + 10 + 'px';
			left_pos = $(window).scrollLeft() + 5 + 'px';

			lb.css('top', top_pos).css('left', left_pos).show();
		});
	});
</script>