<?php
/**
 * Install completion page
 */

echo elgg_autop(elgg_echo('install:complete:instructions'));

?>

<div class="elgg-install-nav">
<?= elgg_format_element('a', [
	'href' => elgg_get_site_url(),
	'class' => 'elgg-button elgg-button-action',
], elgg_echo('install:complete:gotosite')) ?>
</div>
