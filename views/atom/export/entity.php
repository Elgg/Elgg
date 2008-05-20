<?php
	/**
	 * Elgg Entity export.
	 * Displays an entity as ODD over Atom
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	$entity = $vars['entity'];
	
	echo export($entity->guid, new ODDAtomWrapperFactory());
?>