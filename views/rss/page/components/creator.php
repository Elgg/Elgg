<?php
/**
 * Creator view
 *
 * Implements Dublin Core creator
 *
 * @uses $vars['entity']
 */

$owner = $vars['entity']->getOwnerEntity();
if ($owner) {
	$owner_name = htmlspecialchars($owner->name, ENT_NOQUOTES, 'UTF-8');
	echo "<dc:creator>$owner_name</dc:creator>";
}
