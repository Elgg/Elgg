<?php
/**
 * Tab navigation
 *
 * @uses string $vars['type'] horizontal || vertical - Defaults to horizontal
 * @uses string $vars['class'] Additional class to add to ul
 * @uses array $vars['tabs'] A multi-dimensional array of tab entries in the format array(
 * 	'text' => string, // The string between the <a></a> tags. If not set, 'title' parameter will be used instead
 * 	'href' => string, // URL for the link
 * 	'class' => string  // Class of the li element
 * 	'id' => string, // ID of the li element
 * 	'selected' => bool // if this li element is currently selected
 * 	'url_class' => string, // Class to pass to the link
 * 	'url_id' => string, // ID to pass to the link
 * )
 */
$options = elgg_clean_vars($vars);

$type = elgg_extract('type', $vars, 'horizontal');

if ($type == 'horizontal') {
	$options['class'] = "elgg-tabs elgg-htabs";
} else {
	$options['class'] = "elgg-tabs elgg-vtabs";
}
if (isset($vars['class'])) {
	$options['class'] = "{$options['class']} {$vars['class']}";
}

unset($options['tabs']);
unset($options['type']);

$options = elgg_format_attributes($options);

if (isset($vars['tabs']) && is_array($vars['tabs']) && !empty($vars['tabs'])) {
	?>
	<ul <?php echo $options ?>>
		<?php
		foreach ($vars['tabs'] as $info) {
			$class = elgg_extract('class', $info, '');
			$id = elgg_extract('id', $info, '');

			$selected = elgg_extract('selected', $info, FALSE);
			if ($selected) {
				$class .= ' elgg-state-selected';
			}

			$class_str = ($class) ? "class=\"$class\"" : '';
			$id_str = ($id) ? "id=\"$id\"" : '';

			$options = $info;
			unset($options['class']);
			unset($options['id']);
			unset($options['selected']);

			if (!isset($info['href']) && isset($info['url'])) {
				$options['href'] = $info['url'];
				unset($options['url']);
			}
			if (!isset($info['text']) && isset($info['title'])) {
				$options['text'] = $options['title'];
				unset($options['title']);
			}
			if (isset($info['url_class'])) {
				$options['class'] = $options['url_class'];
				unset($options['url_class']);
			}

			if (isset($info['url_id'])) {
				$options['id'] = $options['url_id'];
				unset($options['url_id']);
			}

			$link = elgg_view('output/url', $options);

			echo "<li $id_str $class_str>$link</li>";
		}
		?>
	</ul>
	<?php
}
