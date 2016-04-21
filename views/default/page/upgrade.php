<?php
/**
 * Page shell for upgrade script
 *
 * Displays an ajax loader until upgrade is complete
 * 
 * @uses $vars['head']        Parameters for the <head> element
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 * @uses $vars['forward']     A relative path to forward to after upgrade. Defaults to /admin
 * @uses $vars['errors']      Errors from the UpgradeService
 */

$errors = elgg_extract('errors', $vars);

$next_url = elgg_http_add_url_query_elements(elgg_get_site_url() . 'upgrade.php', array(
	'upgrade' => 'upgrade',
	'forward' => elgg_extract('forward', $vars, '/admin')
));

// render content before head so that JavaScript and CSS can be loaded. See #4032
$head = elgg_view('page/elements/head', $vars['head']);

if ($errors) {
	$link_attrs = [
		'href' => $next_url,
		'onclick' => 'this.disabled = true',
		'class' => 'elgg-button elgg-button-submit',
	];
} else {
	$head .= elgg_format_element("meta", [
		'http-equiv' => 'refresh',
		'content' => "1;url=$next_url",
	]);
}

ob_start(); // capture body
?>

<div style='margin-top:200px'>
	<?= elgg_view('graphics/ajax_loader', array('hidden' => false)) ?>
</div>

<?php if ($errors): ?>

<div style='margin:30px auto; max-width:60em'>
	<h3 class="mbm">There were errors that you may want to address before continuing:</h3>
	<ul>
		<?php foreach ($errors as $error): ?>
		<li>
			<?= htmlspecialchars($error['public_msg']); ?>
			<?php if (elgg_is_admin_logged_in() && !empty($error['admin_data'])): ?>
			<pre><?= htmlspecialchars(var_export($error['admin_data'], true)) ?></pre>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>
	<p><?= elgg_format_element('a', $link_attrs, 'Continue upgrade') ?></p>
</div>

<?php endif; ?>

<?php
$body = ob_get_clean();

echo elgg_view("page/elements/html", array("head" => $head, "body" => $body));
