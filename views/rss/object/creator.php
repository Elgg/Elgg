<?php
/**
 * 
 */

if ($owner = $vars['entity']->getOwnerEntity()) {
	echo "<dc:creator>{$owner->name}</dc:creator>";
}
