<?php
/**
 * Elgg drop-down login form
 */
if (elgg_is_logged_in()) {
	return true;
}

$body = elgg_view_form('login', [], ['returntoreferer' => true]);
?>
<div id="login-dropdown" class="dropdown">
	<?php
	echo elgg_view('output/url', [
		'id' => 'login-dropdown-box-link',
		'href' => elgg_get_login_url([], '#login-dropdown-box'),
		'text' => elgg_echo('login'),
		'dropdown' => elgg_view('core/account/login_box', [
			'title' => '',
			'id' => 'login-dropdown-box',
		]),
		'dropdown_class' => 'dropdown-menu-right',
	]);
	?>
</div>
