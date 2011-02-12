<?php
/**
 * Page edit form body
 *
 * @package ElggPages
 */

$variables = elgg_get_config('pages');
foreach ($variables as $name => $type) {
?>
<div>
	<label><?php echo elgg_echo("pages:$name") ?></label><br />
	<?php echo elgg_view("input/$type", array(
			'internalname' => $name,
			'value' => $vars[$name],
		));
	?>
</div>
<?php
}

$cats = elgg_view('categories', $vars);
if (!empty($cats)) {
	echo $cats;
}


echo '<div>';
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

echo '</div>';
