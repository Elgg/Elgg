<?php
/**
 * Page edit form.
 *
 * @package ElggPages
 */

$variables = elgg_get_config('pages');
foreach ($variables as $name => $type) {
?>
<p>
	<label><?php echo elgg_echo("pages:$name") ?></label><br />
	<?php echo elgg_view("input/$type", array(
			'internalname' => $name,
			'value' => $vars[$name],
		));
	?>
</p>
<?php
}

$cats = elgg_view('categories', $vars);
if (!empty($cats)) {
	echo "<p>$cats</p>";
}


echo '<p>';
if ($vars['guid']) {
	echo elgg_view('input/hidden', array(
		'internalname' => 'page_guid',
		'value' => $vars['guid'],
	));
}
echo elgg_view('input/hidden', array(
	'internalname' => 'container_guid',
	'value' => $vars['container_guid'],
));
if ($vars['parent_guid']) {
	echo elgg_view('input/hidden', array(
		'internalname' => 'parent_guid',
		'value' => $vars['parent_guid'],
	));
}

echo elgg_view('input/submit', array('value' => elgg_echo('save')));

echo '</p>';
