<?php
	/**
	 * Elgg forgotten password.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	$form_body = "<p>" . elgg_echo('user:password:text') . "</p>";
	$form_body .= "<p><b>". elgg_echo('username') . "</b> " . elgg_view('input/text', array('internalname' => 'username')) . "</p>";
	$form_body .= "<p>" . elgg_view('input/submit', array('value' => elgg_echo('request'))) . "</p>";
?>
<div class="contentWrapper">
	<?php echo elgg_view('input/form', array('action' => "{$vars['url']}action/user/requestnewpassword", 'body' => $form_body)); ?>
</div>