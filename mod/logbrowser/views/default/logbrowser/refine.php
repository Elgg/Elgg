<?php
/**
 * Log browser search form
 *
 * @package ElggLogBrowser
 */

$form_vars = array(
	'method' => 'get',
	'action' => 'admin/administer_utilities/logbrowser',
	'disable_security' => true,
);
$form = elgg_view_form('logbrowser/refine', $form_vars, $vars);

$toggle_link = elgg_view('output/url', array(
	'href' => '#log-browser-search-form',
	'text' => elgg_echo('logbrowser:search'),
	'rel' => 'toggle',
));

?>

<div id="logbrowser-search-area" class="mbm">
	<div>
		<?php echo $toggle_link; ?>
	</div>
	<div id="log-browser-search-form" class="elgg-module elgg-module-inline hidden">
		<div class="elgg-head">
			<h3><?php echo elgg_echo('logbrowser:search'); ?></h3>
		</div>
		<div class="elgg-body">
			<?php echo $form; ?>
		</div>
	</div>
</div>