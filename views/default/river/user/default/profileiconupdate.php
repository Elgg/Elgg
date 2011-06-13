<?php
/**
 * Update avatar river view
 */
$subject = $vars['item']->getSubjectEntity();

$subject_icon = elgg_view_entity_icon($subject, 'tiny');

echo elgg_echo("profile:river:iconupdate");

echo '<div class="elgg-river-content clearfix">';
echo $subject_icon;
echo '</div>';
