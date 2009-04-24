<?php
	/**
	 * CSRF security token view for use with secure forms.
	 * 
	 * It is still recommended that you use input/form.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	$ts = time();
	$token = generate_action_token($ts);
	
	echo elgg_view('input/hidden', array('internalname' => '__elgg_token', 'value' => $token));
	echo elgg_view('input/hidden', array('internalname' => '__elgg_ts', 'value' => $ts));
?>
