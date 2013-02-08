<?php
/**
 * 
 */

/* @var CKEditorUpload */
$entity = $vars['entity'];
$owner = $entity->getOwnerEntity();

$img = elgg_view('output/img', array(
	'src' => $entity->getURL(),
	'style' => 'width: 100%;',
	'class' => 'elgg-photo',
));

echo '<div style="width:200px;">';
echo "<h3>$owner->name</h3>";
echo $img;
echo '</div>';
