<?php
/**
 * Tabbed module component
 * Provides support for inline and ajax tabbing
 *
 * @uses $vars['id']      Optional ID of the module
 * @uses $vars['class']   Additional classes
 * @uses $vars['module']  Module name
 *                        Defaults to 'tabs' (.elgg-module-tabs)
 * @uses $vars['tabs']    An array of tabs suitable for 'navigation/tabs' view
 *                        Each tab should specify either:
 *                        - a 'href' parameter to load content via AJAX, or
 *                        - a 'content' parameter to display content inline
 *                        If the tab uses a 'href' attribute, it should specify
 *                        whether the contents should be reloaded on a subsequent
 *                        click via 'data-ajax-reload' parameter; by default,
 *                        all tabs will be reloading on subsequent clicks.
 *                        You can pass additional data to the ajax view via
 *                        data-ajax-query attribute (json encoded string).
 *                        You can also set the data-ajax-href parameter of the tab,
 *                        which will override the href parameter, in case you want
 *                        to ensure the tab is clickable even before the JS is bootstrapped.
 *                        <code>
 *                        [
 *                           ['text' => 'Tab 1', 'href' => '/dynamic', 'data-ajax-reload' => true],
 *                           ['text' => 'Tab 2', 'href' => '/static', 'data-ajax-reload' => false],
 *                           ['text' => 'Tab 3', 'href' => '/static', 'data-ajax-reload' => false, 'data-ajax-query' => json_encode($data)],
 *                           ['text' => 'Tab 3', 'href' => '/page', 'data-ajax-href' => '/ajax/page'],
 *                           ['text' => 'Tab 4', 'content' => 'Tab content'],
 *                        ]
 *                        </code>
 */
$id = elgg_extract('id', $vars);
if (!isset($vars['id'])) {
	$id = "elgg-tabs-" . base_convert(mt_rand(), 10, 36);
}
$vars['id'] = $id;

$vars['class'] = elgg_extract_class($vars, 'elgg-tabs-component');

$tabs = (array) elgg_extract('tabs', $vars, []);
unset($vars['tabs']);
if (empty($tabs)) {
	return;
}

$content = '';
foreach ($tabs as $index => $tab) {
	if (!isset($tab['href']) && !isset($tab['content'])) {
		elgg_log('Tab configuration in "page/components/tabs" requires either a "href" or "content" parameter', 'NOTICE');
		continue;
	}

	$selected = elgg_extract('selected', $tab);

	$class = ['elgg-content'];
	$tab_content = '';
	$tab_id = "{$id}-{$index}";
	if (isset($tab['content'])) {
		$class[] = $selected ? 'elgg-state-active' : 'hidden';

		$tab_content = elgg_extract('content', $tab);
		unset($tab['content']);

		$tab['href'] = "#{$tab_id}";
	} else {
		if (!isset($tab['data-ajax-reload'])) {
			$tab['data-ajax-reload'] = true;
		}
		
		$class[] = 'hidden';
	}
	
	// place for content
	$content .= elgg_format_element('div', [
		'class' => $class,
		'id' => $tab_id,
	], $tab_content);
	
	// additional tab information
	if (!isset($tab['name'])) {
		$tab['name'] = "{$id}-tab-{$index}";
	}
	
	$tab['data-target'] = "#{$tab_id}";
	$tab['item_class'] = elgg_extract_class($tab, ['elgg-components-tab'], 'item_class');
	
	$tabs[$index] = $tab;
}

$tabs = elgg_view('navigation/tabs', [
	'class' => 'elgg-components-tabs',
	'tabs' => $tabs,
]);

$content = elgg_format_element('div', [
	'class' => 'elgg-tabs-content',
], $content);

$module = elgg_extract('module', $vars, 'tabs');
unset($vars['module']);

echo elgg_view_module($module, elgg_extract('title', $vars), $tabs . $content, $vars);

elgg_require_js('page/components/tabs');
