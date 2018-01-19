<?php
/**
 * Creator view
 *
 * Implements Dublin Core creator
 *
 * @uses $vars['entity']
 */

$owner = elgg_extract('entity', $vars)->getOwnerEntity();
if ($owner) {
	$owner_name = htmlspecialchars($owner->getDisplayName(), ENT_NOQUOTES, 'UTF-8');
	echo "<dc:creator>$owner_name</dc:creator>";
}
