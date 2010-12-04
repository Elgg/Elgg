<?php
/**
 * Tab navigation
 *
 * @uses string $vars['type'] horizontal || vertical - Defaults to horizontal (vertical TBI)
 * @uses array $vars['tabs'] A multi-dimensional array of tab entries in the format array(
 * 	'title' => string, // Title of link
 * 	'url' => string, // URL for the link
 * 	'url_js' => string, // JS to pass to the link
 * 	'url_class' => string, // Class to pass to the link
 * 	'class' => string  // Class of the li element.
 * 	'selected' => bool // if this link is currently selected
 * )
 **/

$type = (isset($vars['type'])) ? $vars['type'] : 'horizontal';
if ($type == 'horizontal') {
	$type_class = "elgg-horizontal-tabbed-nav margin-top";
} else {
	$type_class = "elgg_vertical_tabbed_nav";
}

if (isset($vars['tabs']) && is_array($vars['tabs']) && !empty($vars['tabs'])) {
	?>
	<div class="<?php echo $type_class; ?>">
		<ul>
	<?php
	foreach ($vars['tabs'] as $info) {
		$class = (isset($info['class'])) ? $info['class'] : '';

		if (isset($info['selected']) && $info['selected'] == TRUE) {
			$class .= ' selected';
		}

		$class_str = ($class) ? "class=\"$class\"" : '';
		$title = htmlentities($info['title'], ENT_QUOTES, 'UTF-8');
		$url = htmlentities($info['url'], ENT_QUOTES, 'UTF-8');

		$options = array(
			'href' => $url,
			'title' => $title,
			'text' => $title
		);

		if (isset($info['url_js'])) {
			$options['js'] = $info['url_js'];
		}

		if (isset($info['url_class'])) {
			$options['class'] = $info['url_class'];
		}

		$link = elgg_view('output/url', $options);

		echo "<li $class_str $js>$link</li>";
	}
	?>
		</ul>
	</div>
	<?php
}