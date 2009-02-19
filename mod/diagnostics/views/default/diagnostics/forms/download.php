<?php
	/**
	 * Elgg diagnostics
	 * 
	 * @package ElggDiagnostics
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */
	
	$form_body = elgg_view('input/submit', array('value' => elgg_echo('diagnostics:download')));
	echo elgg_view('input/form', array('body' => $form_body, 'action' => $CONFIG->url . "action/diagnostics/download"));
?>