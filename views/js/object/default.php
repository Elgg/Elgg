<?php
/**
 * Elgg JS default view
 *
 * @package Elgg
 * @subpackage Core
 */

if (isset($vars['entity'])) {
	echo elgg_view_entity($vars['entity'],true,false);
}