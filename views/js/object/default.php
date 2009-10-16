<?php
/**
 * Elgg JS default view
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

if (isset($vars['entity'])) {
	echo elgg_view_entity($vars['entity'],true,false);
}