<?php
/**
 * Elgg login box
 *
 * @package Elgg
 * @subpackage Core
 */

$form_body = elgg_view('account/forms/login');

$login_url = elgg_get_site_url();
if ((isset($CONFIG->https_login)) && ($CONFIG->https_login)) {
	$login_url = str_replace("http:", "https:", elgg_get_site_url());
}
?>

<div id="login">
<h2><?php echo elgg_echo('login'); ?></h2>
	<?php
		echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$login_url}action/login"));
	?>
</div>
<script type="text/javascript">
	$(document).ready(function() { $('input[name=username]').focus(); });
</script>
