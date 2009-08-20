<?php
	/**
	 * CSRF security token view for use with secure forms.
	 * 
	 * It is still recommended that you use input/form.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 */

	$ts = time();
	$token = generate_action_token($ts);
	
	echo elgg_view('input/hidden', array('internalname' => '__elgg_token', 'value' => $token));
	echo elgg_view('input/hidden', array('internalname' => '__elgg_ts', 'value' => $ts));
?>
