<?php
/**
 * Elgg login box
 *
 * @package Elgg
 * @subpackage Core
 */

$login_url = elgg_get_site_url();
if (elgg_get_config('https_login')) {
	$login_url = str_replace("http:", "https:", $login_url);
}

$title = elgg_echo('login');
$body = elgg_view_form('login', array('action' => "{$login_url}action/login"));

echo elgg_view_module('aside', $title, $body);

?>


<script type="text/javascript">
	elgg.register_hook_handler('init', 'system', function() {
		$('input[name=username]').focus(); 
	});
</script>
