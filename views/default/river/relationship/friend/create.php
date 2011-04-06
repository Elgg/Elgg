<?php
/**
 * Create friend river view
 */
$subject = $vars['item']->getSubjectEntity();
$object = $vars['item']->getObjectEntity();

$params = array(
	'href' => $object->getURL(),
	'text' => $object->name,
);
$object_link = elgg_view('output/url', $params);
$subject_icon = elgg_view_entity_icon($subject, 'tiny');
$object_icon = elgg_view_entity_icon($object, 'tiny');

echo elgg_echo("friends:river:add", array($object_link));

echo '<div class="elgg-river-content clearfix">';
echo $subject_icon;
echo elgg_view_icon('arrow-right', true);
echo $object_icon;
echo '</div>';
