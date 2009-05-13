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

	
?>
<div class="test">
	<div class="test_header">
		<b><?php echo $vars['function']; ?></b>
	</div>
	<div class="test_description">
		<?php echo $vars['description']; ?>
	</div>
	<div class="test_button">
		<?php
		
			$form_body = elgg_view('input/submit', array('internalname' => 'execute', 'value' => elgg_echo('diagnostics:test:executetest')));
		
			echo elgg_view('input/form', array('action' => $vars['url'] . "pg/diagnostics/tests/{$vars['function']}", 'body' => $form_body));
		?>
	</div>
</div>