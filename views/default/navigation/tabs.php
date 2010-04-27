<?php
/**
 * Tab navigation
 *
 * @uses string $vars['type'] horizontal || vertical - Defaults to horizontal (vertical TBI)
 * @uses array $vars['tabs'] A multi-dimensional array of tab entries in the format array(
 * 	'title' => string, // Title of link
 * 	'url' => string, // URL for the link
 * 	'class' => string  // Class of the li element.
 * 	'selected' => bool // if this link is currently selected
 * )
 **/

$type = (isset($vars['type'])) ? $vars['type'] : 'horizontal';
if ($type == 'horizontal') {
	$type_class = "elgg_horizontal_tabbed_nav margin_top";
} else {
	$type_class = "elgg_vertical_tabbed_nav";
}

if (isset($vars['tabs'])) {
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

		echo "<li $class_str $js><a href=\"$url\" title=\"$title\"><span>$title</span></a></li>";
	}
	?>
		</ul>
	</div>
	<?php
}