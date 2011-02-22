<?php
/**
 * Elgg list view switcher
 *
 * @package Elgg
 * @subpackage Core
 * 
 * @deprecated 1.8 Use navigation/listtype
 */

elgg_deprecated_notice('navigation/viewtype was deprecated by navigation/listtype', 1.8);

echo elgg_view('navigation/listtype', $vars);