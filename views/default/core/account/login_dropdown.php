<?php
/**
 * Elgg drop-down login form
 */

if (elgg_is_logged_in()) {
	return true;
}

$body = elgg_view_form('login', array(), array('returntoreferer' => TRUE));
?>
<div id="login-dropdown">
	<?php 
		echo elgg_view('output/url', array(
			'href' => 'login#login-dropdown-box',
			'rel' => 'popup',
			'class' => 'elgg-button elgg-button-dropdown',
			'text' => elgg_echo('login'),
		)); 
		echo elgg_view_module('dropdown', '', $body, array('id' => 'login-dropdown-box')); 
	?>
</div>
