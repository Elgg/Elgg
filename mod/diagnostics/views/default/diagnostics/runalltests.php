<?php
	/**
	 * Elgg diagnostics - unit tester
	 * 
	 * @package ElggDiagnostics
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	$form_body .= elgg_view('input/submit', array('internalname' => 'execute', 'value' => elgg_echo('diagnostics:test:executeall')));

	echo elgg_view('input/form', array('action' => $vars['url'] . "pg/diagnostics/tests/all", 'body' => $form_body));	
?>