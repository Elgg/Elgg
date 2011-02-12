<?php
/**
 * Elgg file upload/save form
 *
 * @package ElggFile
 */

// once elgg_view stops throwing all sorts of junk into $vars, we can use 
$title = elgg_get_array_value('title', $vars, '');
$desc = elgg_get_array_value('description', $vars, '');
$tags = elgg_get_array_value('tags', $vars, '');
$access_id = elgg_get_array_value('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_get_array_value('container_guid', $vars);
$guid = elgg_get_array_value('guid', $vars, null);
$ajax = elgg_get_array_value('ajax', $vars, FALSE);

if ($guid) {
	$file_label = elgg_echo("file:replace");
} else {
	$file_label = elgg_echo("file:file");
}

?>
<p>
	<label><?php echo $file_label; ?></label><br />
	<?php echo elgg_view('input/file', array('internalname' => 'upload')); ?>
</p>
<p>
	<label><?php echo elgg_echo('title'); ?></label><br />
	<?php echo elgg_view('input/text', array('internalname' => 'title', 'value' => $title)); ?>
</p>
<p>
	<label><?php echo elgg_echo('description'); ?></label>
	<?php echo elgg_view('input/longtext', array('internalname' => 'description', 'value' => $desc)); ?>
</p>
<p>
	<label><?php echo elgg_echo('tags'); ?></label>
	<?php echo elgg_view('input/tags', array('internalname' => 'tags', 'value' => $tags)); ?>
</p>
<?php

$categories = elgg_view('input/categories', $vars);
if ($categories) {
	echo "<p>$categories</p>";
}

?>
<p>
	<label><?php echo elgg_echo('access'); ?></label><br />
	<?php echo elgg_view('input/access', array('internalname' => 'access_id', 'value' => $access_id)); ?>
</p>
<p>
<?php

echo elgg_view('input/hidden', array('internalname' => 'container_guid', 'value' => $container_guid));

//@todo this should not be necessary in 1.8... -- ajax actions can be auto-detected
if ($ajax) {
	echo elgg_view('input/hidden', array('internalname' => 'ajax', 'value' => 1));
}

if ($guid) {
	echo elgg_view('input/hidden', array('internalname' => 'file_guid', 'value' => $guid));
}

echo elgg_view('input/submit', array('value' => elgg_echo("save")));

?>
</p>
