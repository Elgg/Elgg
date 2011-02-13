<?php
/**
 * Elgg drop-down login form
 *
 * @todo Forms 1.8: Convert to use elgg_view_form()
 */

if (elgg_is_logged_in()) {
	return true;
}

/**
 * @todo forms/login should take a "forward_to" argument, or something similar
 * Enter description here ...
 * @var unknown_type
 */
$form_body = elgg_view('forms/login');

$login_url = elgg_get_site_url();
if (elgg_get_config('https_login')) {
	$login_url = str_replace("http", "https", elgg_get_site_url());
}

$body = elgg_view_form('login', array('action' => "{$login_url}action/login"), array('returntoreferer' => TRUE));
?>

<div id="login-dropdown">
	<a href="#" class="elgg-toggle signin" id="elgg-toggler-login-dropdown-box">
		<?php echo elgg_echo('login') ?>
	</a>
	<?php echo elgg_view_module('dropdown', '', $body, array('id' => 'elgg-togglee-login-dropdown-box', 'class' => 'hidden')); ?>
</div>
<script>
$(function() {
	$('.signin').live('click', function() {
		e.preventDefault();
	});
});
</script>