<?php

     /**
	 * Elgg login form
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */
	 
	global $CONFIG;
	
	$form_body = "<p class=\"loginbox\"><label>" . elgg_echo('username') . "<br />" . elgg_view('input/text', array('internalname' => 'username', 'class' => 'login-textarea')) . "</label>";
	$form_body .= "<br />";
	$form_body .= "<label>" . elgg_echo('password') . "<br />" . elgg_view('input/password', array('internalname' => 'password', 'class' => 'login-textarea')) . "</label><br />";
	$form_body .= elgg_view('input/submit', array('value' => elgg_echo('login'))) . " <div id=\"persistent_login\"><label><input type=\"checkbox\" name=\"persistent\" value=\"true\" />".elgg_echo('user:persistent')."</label></div></p>";
	$form_body .= "<p class=\"loginbox\">";
	$form_body .= (!isset($CONFIG->disable_registration) || !($CONFIG->disable_registration)) ? "<a href=\"{$vars['url']}pg/register.php\">" . elgg_echo('register') . "</a> | " : "";
	
	//<input name=\"username\" type=\"text\" class="general-textarea" /></label>
?>
	
	<div id="login-box">
	<h2><?php echo elgg_echo('login'); ?></h2>
		<?php echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$vars['url']}action/login")); ?>
		
	</div>
