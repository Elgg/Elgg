<?php
/**
 * Elgg drop-down login form
 *
 * @todo Forms 1.8: Convert to use elgg_view_form()
 */

if (elgg_is_logged_in()) {
	return true;
}

$login_url = elgg_get_site_url();
if (elgg_get_config('https_login')) {
	$login_url = str_replace("http", "https", elgg_get_site_url());
}

$body = elgg_view_form('login', array('action' => "{$login_url}action/login"), array('returntoreferer' => TRUE));
?>
<div id="login-dropdown">
	<a href="<?php echo $vars['url']; ?>pg/login" class="elgg-toggler elgg-toggles-login-dropdown-box elgg-button-dropdown">
		<?php echo elgg_echo('login') ?>
	</a>
	<?php echo elgg_view_module('dropdown', '', $body, array('id' => 'login-dropdown-box')); ?>
</div>