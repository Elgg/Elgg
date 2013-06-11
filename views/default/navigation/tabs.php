<?php
/**
 * Tab navigation
 *
 * @uses string $vars['type'] horizontal || vertical - Defaults to horizontal
 * @uses string $vars['class'] Additional class to add to ul
 * @uses array $vars['tabs'] A multi-dimensional array of tab entries in the format array(
 * 	'text' => string, // The string between the <a></a> tags
 * 	'href' => string, // URL for the link
 * 	'class' => string  // Class of the li element
 * 	'id' => string, // ID of the li element
 * 	'selected' => bool // if this tab is currently selected (applied to li element)
 * 	'link_class' => string, // Class to pass to the link
 * 	'link_id' => string, // ID to pass to the link
 * )
 */
$options = _elgg_clean_vars($vars);

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

$attributes = elgg_format_attributes($options);

if (isset($vars['tabs']) && is_array($vars['tabs']) && !empty($vars['tabs'])) {
	?>
	<ul <?php echo $attributes; ?>>
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
			if (isset($info['link_class'])) {
				$options['class'] = $options['link_class'];
				unset($options['link_class']);
			}

			if (isset($info['link_id'])) {
				$options['id'] = $options['link_id'];
				unset($options['link_id']);
			}

			$link = elgg_view('output/url', $options);

			echo "<li $id_str $class_str>$link</li>";
		}
		?>
	</ul>
	<?php
}
